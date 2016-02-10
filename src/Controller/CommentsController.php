<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 */
class CommentsController extends AppController
{

    //Individual access rules to projects functions (projects/*).
    public function isAuthorized($user)
    {

        /* Load all the needed tables in a common place.
         * All queries are lazy so the most this will do
         * is load the columns of the given table
         * but even this will be cached automatically by
         * cakephp
         */ 
        $Projects = TableRegistry::get('Projects');
        $ProjectsUsers = TableRegistry::get('ProjectsUsers');
        $ProjectsTickets = TableRegistry::get('ProjectsTickets');
        $TicketsUsers = TableRegistry::get('TicketsUsers');
        $TicketsComments = TableRegistry::get('TicketsComments');

        // Owners/Admins of a project and assigned users can add comments.
        if (in_array($this->request->action, ['add'])){
            
            $ticketId = (int)$this->request->params['pass'][0];

            $projectId = $ProjectsTickets->find()
                ->where(['ticket_id' => $ticketId])
                ->first()['project_id'];

            // Project owners.
            if ($Projects->isOwnedBy($projectId, $user['id'])){
                return true;
            }

            // Project admins.
            if ($ProjectsUsers->isModeratedBy($projectId, $user['id'])){
                return true;
            }

            // Users assigned to ticket.
            if ($TicketsUsers->isAssignedTo($ticketId, $user['id'])){
                return true;
            }

        } else {

            $commentId = (int)$this->request->params['pass'][0];
            
            $ticketId = $TicketsComments->find()
                ->where(['comment_id' => $commentId])
                ->first()['ticket_id'];

            $projectId = $ProjectsTickets->find()
                ->where(['ticket_id' => $ticketId])
                ->first()['project_id'];

            // Project owners.
            if ($Projects->isOwnedBy($projectId, $user['id'])){
                return true;
            }

            // Project admins.
            if ($ProjectsUsers->isModeratedBy($projectId, $user['id'])){
                return true;
            }

            // Comment owners can't edit or delete their comments.
        }

        // If none of the above default to
        // Admin = true, User = false
        return parent::isAuthorized($user);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($ticketId)
    {
        $comment = $this->Comments->newEntity();
        if ($this->request->is('post')) {
            $comment = $this->Comments->patchEntity($comment, $this->request->data);
            $comment->user_id = $this->Auth->user('id');
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));
                return $this->redirect(['controller' => 'Tickets', 'action' => 'view', $ticketId]);
            } else {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
        }
        $tickets = $this->Comments->Tickets
            ->find('list', ['limit' => 200])
            ->where(['id' => $ticketId]);
            $this->set('ticketId', $ticketId);

        $this->set(compact('comment', 'tickets'));
        $this->set('_serialize', ['comment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => ['Tickets']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->data);
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));


                $TicketsComments = TableRegistry::get('TicketsComments');
                $ticketId = $TicketsComments->find()
                    ->where(['comment_id' => $id])
                    ->first()['ticket_id'];

                return $this->redirect(['controller' => 'Tickets', 'action' => 'view', $ticketId]);
            } else {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
        }
        $tickets = $this->Comments->Tickets->find('list', ['limit' => 200]);
        $this->set(compact('comment', 'tickets'));
        $this->set('_serialize', ['comment']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $TicketsComments = TableRegistry::get('TicketsComments');
            $ticketId = $TicketsComments->find()
                ->where(['comment_id' => $id])
                ->first()['ticket_id'];
            $this->Flash->success(__('The comment has been deleted.'));
            return $this->redirect(['controller' => 'Tickets', 'action' => 'view', $ticketId]);
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
