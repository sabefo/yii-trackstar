<?php
	class ProjectTest extends CDbTestCase {
		public $fixtures = array(
			'projects' => 'Project',
			'users' => 'User',
			'projUsrAssign'=>':tbl_project_user_assignment',
		);

		public function testCreate() {
			$newProject = new Project;
			$newProjectName = 'Test Project Creation';
			$newProject -> setAttributes(array(
				'name' => $newProjectName,
				'description' => 'This is a test for new project creation',
				// 'createTime' => '2009-09-09 00:00:00',
				// 'createUser' => '1',
				// 'updateTime' => '2009-09-09 00:00:00',
				// 'updateUser' => '1',
			));
			Yii::app() -> user -> setId($this -> users('user1') -> id);
			$this -> assertTrue($newProject -> save());

			$retrievedProject = Project::model() -> findByPk($newProject -> id);
			// echo $retrievedProject -> create_user_id;die;
			$this -> assertTrue($retrievedProject instanceof Project);
			$this -> assertEquals($newProjectName, $retrievedProject -> name);
			$this -> assertEquals(Yii::app() -> user -> id, $retrievedProject -> create_user_id);
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

		public function testGetUserOptions() {
			$project = $this -> projects('project2');
			$options = $project -> userOptions;
			$this -> assertTrue(is_array($options));
			$this -> assertTrue(count($options) > 0);
		}
	}
?>
