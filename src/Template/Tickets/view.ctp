<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><h3><?= __('Actions') ?></h3></li>
        <li class="heading"><?= __('Projects') ?></li>
        <li><?= $this->Html->link(__('List Projects'), ['controller' => 'Projects', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Project'), ['controller' => 'Projects', 'action' => 'add']) ?></li>
        <li class="heading"><?= __('Users') ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li class="heading"><?= __('Tickets') ?></li>
        <li><?= $this->Html->link(__('List Tickets'), ['controller' => 'Tickets', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit Ticket'), ['action' => 'edit', $ticket->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Ticket'), ['action' => 'delete', $ticket->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ticket->id)]) ?> </li>
        <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add', $ticket->id]) ?> </li>
        <?php if($isAdmin): ?>
        <li class="heading"><?= __('Tags') ?></li>
        <li><?= $this->Html->link(__('List Tags'), ['controller' => 'Tags', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Tag'), ['controller' => 'Tags', 'action' => 'add']) ?></li>
        <li class="heading"><?= __('Admin Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'adminAdd']) ?></li>
        <?php endif; ?>
    </ul>
</nav>
<div class="tickets view large-9 medium-8 columns content">
    <h3><?= h($ticket->title) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Title') ?></th>
            <td><?= h($ticket->title) ?></td>
        </tr>
        <tr>
            <th><?= __('Status') ?></th>
            <td><?= h($ticket->status) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($ticket->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($ticket->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($ticket->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Body') ?></h4>
        <?= $this->Text->autoParagraph(h($ticket->body)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Projects') ?></h4>
        <?php if (!empty($ticket->projects)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Body') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Tag Id') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ticket->projects as $projects): ?>
            <tr>
                <td><?= h($projects->id) ?></td>
                <td><?= h($projects->title) ?></td>
                <td><?= h($projects->body) ?></td>
                <td><?= h($projects->user_id) ?></td>
                <td><?= h($projects->tag_id) ?></td>
                <td><?= h($projects->created) ?></td>
                <td><?= h($projects->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Projects', 'action' => 'view', $projects->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Projects', 'action' => 'edit', $projects->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Projects', 'action' => 'delete', $projects->id], ['confirm' => __('Are you sure you want to delete # {0}?', $projects->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($ticket->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Username') ?></th>
                <th><?= __('Email') ?></th>
                <th><?= __('Role') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ticket->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->username) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->role) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Comments') ?></h4>
        <?php if (!empty($ticket->comments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Body') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($ticket->comments as $comments): ?>
            <tr>
                <td><?= h($comments->id) ?></td>
                <td><?= h($comments->body) ?></td>
                <td><?= h($comments->created) ?></td>
                <td><?= h($comments->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Comments', 'action' => 'view', $comments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
