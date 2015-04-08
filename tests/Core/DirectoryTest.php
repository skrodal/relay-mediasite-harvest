<?php
class DirectoryTest extends UninettTestCase {

	private $directoy;
	public function setUp()
	{
		$this->directoy = new \Uninett\Core\Directory();
	}

	/** @test */
	public function it_can_get_directoy_path_from_full_file_path_and_with_slash()
	{
		$fullPath = "/a/b/c/d/efg.xml";

		$directoyPath = $this->directoy->getDirectoryPath($fullPath, true);

		$this->assertEquals('/a/b/c/d/', $directoyPath);
	}

	/** @test */
	public function it_can_get_directoy_path_from_full_file_path_without_slash()
	{
		$fullPath = "/a/b/c/d/efg.xml";

		$directoyPath = $this->directoy->getDirectoryPath($fullPath, false);

		$this->assertEquals('/a/b/c/d', $directoyPath);
	}

}