<?php
class ProjectTest extends CDbTestCase
{

    public $fixtures = array(
  		'projects' => 'Project',
  		'users' => 'User',
  		'projUsrAssign' => ':tbl_project_user_assignment',
  		'projUserRole' => ':tbl_project_user_role',
  		'authAssign' => ':AuthAssignment',
    );

    public function testCreate()
    {
      $newProject = new Project;
      $newProjectName = 'Test Project Creation';
      $newProject -> setAttributes(
          array(
            'name' => $newProjectName,
            'description' => 'This is a test for new project creation',
          )
      );
      Yii::app() -> user -> setId($this -> users('user1') -> id);
      $this -> assertTrue($newProject -> save());

      $retrievedProject = Project::model() -> findByPk($newProject -> id);
      // echo $retrievedProject -> create_user_id;die;
      $this -> assertTrue($retrievedProject instanceof Project);
      $this -> assertEquals($newProjectName, $retrievedProject -> name);
      $this -> assertEquals(Yii::app() -> user -> id, $retrievedProject -> create_user_id);
    }

    public function testRead()
    {
      $retrievedProject = $this -> projects('project1');
      $this -> assertTrue($retrievedProject instanceof Project);
      $this -> assertEquals('Test Project 1', $retrievedProject -> name);
    }

    public function testUpdate()
    {
      $retrievedProject = $this -> projects('project1');
      $this -> assertTrue($retrievedProject instanceof Project);
      $newName = 'Test project 1 new name';
      $retrievedProject -> name = $newName;
      $this -> assertTrue($retrievedProject -> save(false));
      $this -> assertEquals($retrievedProject -> name, $newName);
    }

    public function testDelete()
    {
      $retrievedProject = $this -> projects('project1');
      $projectId = $retrievedProject -> id;
      $this -> assertTrue($retrievedProject -> delete());
      $deletedProject = Project::model() -> findByPk($projectId);
      $this -> assertEquals(null, $deletedProject);
    }

    public function testGetUserOptions()
    {
      $project = $this -> projects('project2');
      $options = $project -> userOptions;
      $this -> assertTrue(is_array($options));
      $this -> assertTrue(count($options) > 0);
    }

    public function testUserRoleAssignment()
    {
      $project = $this -> projects('project1');
      $user = $this -> users('user1');
      $this -> assertEquals(1, $project -> associateUserToRole('owner', $user -> id));
	    $this -> assertEquals(1, $project -> removeUserFromRole('owner', $user -> id));
    }

	public function testIsInRole()
	{
		$row1 = $this -> projUserRole['row1'];
		Yii::app() -> user -> setId($row1['user_id']);
		$project = Project::model() -> findByPk($row1['project_id']);
		$this -> assertTrue($project -> isUserInRole('member'));
	}

	public function testUserAccessBasedOnProjectRole()
	{
		$row1 = $this -> projUserRole['row1'];
		Yii::app() -> user -> setId($row1['user_id']);
		$project = Project::model() -> findByPk($row1['project_id']);
		$auth = Yii::app() -> authManager;
		$bizRule = 'return isset($params["project"]) && $params["project"] -> isUserInRole("member");';
		$auth -> assign('member', $row1['user_id'], $bizRule);
		$params = array('project' => $project);
		$this -> assertTrue(Yii::app() -> user -> checkAccess('updateIssue', $params));
		$this -> assertTrue(Yii::app() -> user -> checkAccess('readIssue', $params));
		$this -> assertFalse(Yii::app() -> user -> checkAccess('updateProject', $params));
		//now ensure the user does not have any access to a project they are not associated with
		$project = Project::model() -> findByPk(1);
		$params = array('project' => $project);
		$this -> assertFalse(Yii::app() -> user -> checkAccess('updateIssue', $params));
		$this -> assertFalse(Yii::app() -> user -> checkAccess('readIssue', $params));
		$this -> assertFalse(Yii::app() -> user -> checkAccess('updateProject', $params));
	}

	public function testGetUserRoleOptions()
	{
		$options = Project::getUserRoleOptions();
		$this -> assertEquals(count($options), 3);
		$this -> assertTrue(isset($options['reader']));
		$this -> assertTrue(isset($options['member']));
		$this -> assertTrue(isset($options['owner']));
	}

	public function testUserProjectAssignment()
	{
		//since our fixture data already has the two users assigned to project 1, we'll assign user 1 to project 2
		$this -> projects('project2') -> associateUserToProject($this -> users('user3'));
		$this -> assertTrue($this -> projects('project2') -> isUserInProject($this -> users('user3')));
	}
}
?>
