<?php

use PHPMentors\DomainKata\Entity\EntityInterface;
use PHPMentors\Workflower\Workflow\Workflow;
use PHPMentors\Workflower\Workflow\WorkflowRepositoryInterface;
use PHPMentors\Workflower\Workflow\WorkflowBuilder;

class QLDAWorkflow implements WorkflowRepositoryInterface
{
    private $workflows = array();

    public function __construct()
    {
        $this->add($this->createMultipleWorkItemsProcess());
    }

    public function add(EntityInterface $entity)
    {
        assert($entity instanceof Workflow);
        $this->workflows[$entity->getId()] = $entity;
    }

    public function remove(EntityInterface $entity)
    {
        assert($entity instanceof Workflow);
    }

    public function findById($id)
    {
        if (!array_key_exists($id, $this->workflows)) {
            return null;
        }
        return $this->workflows[$id];
    }

    public function setWorkflow($id) {
        $workflow = $this->findById($id);
        $workflow->start($workflow->getFlowObject('Start'));

        return $workflow;

    }

    private function createMultipleWorkItemsProcess()
    {
        $workflowBuilder = new WorkflowBuilder();
        $workflowBuilder->setWorkflowId('MultipleWorkItemsProcess');
        $workflowBuilder->addRole('ROLE_USER', 'User');
        $workflowBuilder->addStartEvent('Start', 'ROLE_USER');
        $workflowBuilder->addTask('Task1', 'ROLE_USER');
        $workflowBuilder->addTask('Task2', 'ROLE_USER', null, 'Task2.End');
        $workflowBuilder->addEndEvent('End', 'ROLE_USER');
        $workflowBuilder->addSequenceFlow('Start', 'Task1', 'Start.Task1');
        $workflowBuilder->addSequenceFlow('Task1', 'Task2', 'Task1.Task2');
        $workflowBuilder->addSequenceFlow('Task2', 'End', 'Task2.End');
        $workflowBuilder->addSequenceFlow('Task2', 'Task1', 'Task2.Task1', null, 'satisfied !== true');
        return $workflowBuilder->build();
    }
}