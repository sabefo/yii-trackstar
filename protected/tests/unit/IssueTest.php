<?php
class IssueTest extends CDbTestCase
{
    public $fixtures = array(
    'projects' => 'Project',
    'issues' => 'Issue',
    );

    public function testGetTypes() 
    {
        $options = Issue::model() -> typeOptions;
        $this -> assertTrue(is_array($options));
        $this -> assertTrue(count($options) == 3);
        $this -> assertTrue(in_array('Bug', $options));
        $this -> assertTrue(in_array('Feature', $options));
        $this -> assertTrue(in_array('Task', $options));
    }

    public function testGetStatuses() 
    {
        $options = Issue::model() -> statusOptions;
        $this -> assertTrue(is_array($options));
        $this -> assertTrue(count($options) == 3);
        $this -> assertTrue(in_array('Not yet started', $options));
        $this -> assertTrue(in_array('Started', $options));
        $this -> assertTrue(in_array('Finished', $options));
    }

    public function testGetStatusText() 
    {
        $this -> assertTrue($this -> issues('issueBug') -> getStatusText() == 'Started');
    }

    public function testGetTypeText() 
    {
        $this -> assertTrue($this -> issues('issueBug') -> getTypeText() == 'Bug');
    }
}
?>
