<?php // vim:ts=3:sts=3:sw=3:et:

/**
 * This class grabs the specified number of lines from the target
 * file and caches them. Multiple searches can be performced
 * against the cached lines
 *
 * @author Bryan Geraghty <bryan@ravensight.org>
 */
class MindFrame2_TailGrep
{
	/**
	 * @var resource
	 */
	protected $file_pointer;

	/**
	 * @var string
	 */
	protected $file_name;

	/**
	 * @var int
	 */
	protected $line_count;

	/**
	 * @var string
	 */
	protected $buffer;

	/**
	 * Construct
	 *
	 * @param string $file_name The target file to be searched
	 * @param int $line_count Number of lines from the end of the file to be searched
	 */
	public function __construct($file_name, $line_count)
	{
		if (!is_int($line_count))
		{
			throw new InvalidArgumentException('Expected integer value for argument #2 (line_count)');
		}

		$this->file_name = $file_name;
		$this->line_count = $line_count;
	}

	/**
	 * Help out the garbage collector
	 */
	public function __destruct()
	{
		$this->closeFileIfOpen();
		unset($this->file_pointer, $this->file_name, $this->lines);
	}

	/**
	 * Performs the pattern matching
	 *
	 * @param string $expression The string to look for
	 *
	 * @return string
	 */
	public function grep($expression)
	{
		$this->fetchLinesOnce();

		$array = explode("\n", $this->buffer);
		$matches = preg_grep('/' . $expression . '/i', $array);
		$results = join("\n", $matches);

		return $results;
	}

   /**
    * Explodes the entire buffer into an array
    *
    * @return array
    */
   public function fetchAll()
   {
      $this->fetchLinesOnce();

		return explode("\n", $this->buffer);
   }

	/**
	 * Fetches the specified number of lines from the specified file into the
	 * internal buffer
	 *
	 * @return bool
	 */
	public function fetchLines()
	{
		$this->openFileOnce();

		$read_length = 8;
		$buffer = NULL;
		$line_count = 0;

		fseek($this->file_pointer, 0, SEEK_END);

		while (($line_count <= ($this->line_count)) && ($read_length > 0))
		{
			if ($this->readChunk($read_length, $buffer) === FALSE)
			{
				throw new RuntimeException('Failed to read chunk');
			}

			$line_count = $this->countLines($buffer);
			$read_length = $this->adjustReadLength($line_count, $read_length, strlen($buffer));
		}
		// end while // ($line_count <= ($this->line_count))... //

		$this->trimBuffer($buffer);
		$this->buffer = $buffer;

		return TRUE;
	}

	/**
	 * Returns the size of the target file in bytes
	 *
	 * @return int
	 */
	protected function fetchFileSize()
	{
		return filesize($this->file_name);
	}

	/**
	 * Checks to see if the internal buffer has been populated before fetching the
	 * contents from the file
	 *
	 * @return bool
	 */
	protected function fetchLinesOnce()
	{
		if ($this->buffer === NULL)
		{
			$this->fetchLines();
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Seek to the point where we want to start reading, read, then seek back
	 * to the point where we started reading
	 *
	 * @param int $read_length How many bytes to read
	 * @param string &$buffer The buffer to place the read contents into
	 *
	 * @return bool
	 */
	protected function readChunk($read_length, &$buffer)
	{
		if (!is_int($read_length))
		{
			throw new InvalidArgumentException('Expected integer value for argument #1 (read_length)');
		}

		fseek($this->file_pointer, -$read_length, SEEK_CUR);
		$read = fread($this->file_pointer, $read_length);
		fseek($this->file_pointer, -$read_length, SEEK_CUR);

		if ($read === FALSE)
		{
			return FALSE;
		}

		$buffer = $read . $buffer;

		return TRUE;
	}

	/**
	 * If we haven't reached the half-way point, double the read length to
	 * speed things up. If the adjustment ends up being being begger than the file,
	 * re-adjust
	 *
	 * @param int $line_count The current number of lines in the buffer
	 * @param int $read_length The current number of bytes to read in each chunk
	 * @param int $buffer_size The current size of the buffer in bytes
	 *
	 * @return int
	 */
	protected function adjustReadLength($line_count, $read_length, $buffer_size)
	{
		if (!is_int($line_count))
		{
			throw new InvalidArgumentException('Expected integer value for argument #1 (line_count)');
		}

		if (!is_int($read_length))
		{
			throw new InvalidArgumentException('Expected integer value for argument #2 (read_length)');
		}

		if (!is_int($buffer_size))
		{
			throw new InvalidArgumentException('Expected integer value for argument #3 (buffer_size)');
		}

		$new_read_length = $read_length;
		$file_size = $this->fetchFileSize();

		if ($line_count <= ($this->line_count / 2))
		{
			$new_read_length = ($read_length * 2);
		}

		if (($buffer_size + $new_read_length) > $file_size)
		{
			$new_read_length = ($file_size - $buffer_size);
		}

		return $new_read_length;
	}

	/**
	 * Removes any excess lines from the buffer because reading from the end of a
	 * file with inconsistent line lengths is not an exact process.
	 *
	 * @param string &$buffer The buffer containing the file contents
	 *
	 * @return bool
	 */
	protected function trimBuffer(&$buffer)
	{
		$diff = ($this->countLines($buffer) - $this->line_count);

		$offset = 0;

		if ($diff > 0)
		{
			for ($x = 0; $x < $diff; $x++)
			{
				$offset = strpos($buffer, "\n", $offset) + 1;
			}
			// end for // ($x = 0; $x < $diff; $x++) //

			$buffer = substr($buffer, $offset);

			return TRUE;
		}
		// end if // ($diff > 0) //

		return FALSE;
	}

	/**
	 * Counts the number of newline characters in the specified string. This function
	 * could be improved to provide cross-platform functionality.
	 *
	 * @param string $buffer The buffer containing the file contents
	 *
	 * @return int
	 */
	protected function countLines($buffer)
	{
		$count = 0;
		$offset = 0;

		while (($offset = strpos($buffer, "\n", $offset + 1)) !== FALSE)
		{
			$count++;
		}

		return $count;
	}

	/**
	 * Checks to see if the file pointer has already been opened before making the
	 * call to open the file.
	 *
	 * @return bool
	 */
	protected function openFileOnce()
	{
		if ($this->file_pointer === NULL)
		{
			$this->openFile();
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Opens the internal file pointer
	 *
	 * @return bool
	 */
	protected function openFile()
	{
		if (!file_exists($this->file_name))
		{
			throw new RunTimeException('File could not be found');
		}

		if (!is_readable($this->file_name))
		{
			throw new RunTimeException('Permission denied when opening file for reading');
		}

		$file_pointer = fopen($this->file_name, 'r');

		if ($file_pointer === FALSE)
		{
			throw new RunTimeException('Could not open file');
		}

		$this->file_pointer = $file_pointer;

		return TRUE;
	}

	/**
	 * Closes the internal file pointer
	 *
	 * @return bool
	 */
	protected function closeFileIfOpen()
	{
		if ($this->file_pointer !== NULL)
		{
			return fclose($this->file_pointer);
		}

		return FALSE;
	}
}

?>
