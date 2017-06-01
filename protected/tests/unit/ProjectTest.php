<?php
	class ProjectTest extends CDbTestCase {
		public $fixtures = array(
			'projects' => 'Project',
		);

		public function testCreate() {
			$newProject = new Project;
			$newProjectName = 'Test Project Creation';
			$newProject -> setAttributes(array(
				'name' => $newProjectName,
				'description' => 'This is a test for new project creation',
				'createTime' => '2009-09-09 00:00:00',
				'createUser' => '1',
				'updateTime' => '2009-09-09 00:00:00',
				'updateUser' => '1',
			));
			$this -> assertTrue($newProject -> save(false));

			$retrievedProject = Project::model() -> findByPk($newProject -> id);
			$this -> assertTrue($retrievedProject instanceof Project);
			$this -> assertEquals($newProjectName, $retrievedProject -> name);
		}

		public function testRead() {
			$retrievedProject = $this -> projects('project1');
			$this -> assertTrue($retrievedProject instanceof Project);
			$this -> assertEquals('Test Project 1', $retrievedProject -> name);
		}

		public function testUpdate() {
			$retrievedProject = $this -> projects('project1');
			$this -> assertTrue($retrievedProject instanceof Project);
			$newName = 'Test project 1 new name';
			$retrievedProject -> name = $newName;
			$this -> assertTrue($retrievedProject -> save(false));
			$this -> assertEquals($retrievedProject -> name, $newName);
		}

		public function testDelete() {
			$retrievedProject = $this -> projects('project1');
			$projectId = $retrievedProject -> id;
			$this -> assertTrue($retrievedProject -> delete());
			$deletedProject = Project::model() -> findByPk($projectId);
			$this -> assertEquals(NULL, $deletedProject);
		}
	}
?>
