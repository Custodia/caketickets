<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><h3><?= __('Actions') ?></h3></li>
        <li class="heading"><?= __('Projects') ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['action' => 'add']) ?></li>
        <li><?= $this->Form->postLink(__('Delete Project'), ['action' => 'delete', $project->id], ['confirm' => __('Are you sure you want to delete # {0}?', $project->id)]) ?> </li>
        <li class="heading"><?= __('Users') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li class="heading"><?= __('Tickets') ?></li>
        <li><?= $this->Html->link(__('List Tickets'), ['controller' => 'Tickets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Ticket'), ['controller' => 'Tickets', 'action' => 'add', $project->id]) ?> </li>
        <?php if($isAdmin): ?>
        <li class="heading"><?= __('Tags') ?></li>
        <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?></li>
        <li class="heading"><?= __('Admin Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'adminAdd']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="projects form large-9 medium-8 columns content">
    <?= $this->Form->create($project) ?>
    <fieldset>
        <legend><?= __('Edit Project') ?></legend>
        <?php
            echo $this->Form->input('title');
            echo $this->Form->input('body');
            echo $this->Form->input('tag_id', ['options' => $tags]);
            echo $this->Form->input('users._ids', ['options' => $users]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
