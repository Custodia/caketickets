<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Tickets Controller
 *
 * @property \App\Model\Table\TicketsTable $Tickets
 */
class TicketsController extends AppController
{

    //Individual access rules to tickets functions (tickets/*).
    public function isAuthorized($user)
    {
        // All registered users can view the index.
        if (in_array($this->request->action, ['index'])){
            return true;
        }

        $Projects = TableRegistry::get('Projects');
        $ProjectsUsers = TableRegistry::get('ProjectsUsers');

        if (in_array($this->request->action, ['add'])){
            $projectId = (int)$this->request->params['pass'][0];
            
            if ($Projects->isOwnedBy($projectId, $user['id'])){
                return true;
            }

            if ($ProjectsUsers->isModeratedBy($projectId, $user['id'])){
                return true;
            }
        } else {

            // Get the ticket id.
            $ticketId = (int)$this->request->params['pass'][0];

            $ProjectsTickets = TableRegistry::get('ProjectsTickets');
        

            // Lookup what project this ticket belongs to.
            $projectId = $ProjectsTickets->find()
                ->where(['ticket_id' => $ticketId])
                ->first()['project_id'];

            // The owner of an project can do anything related to it's tickets.
            if (in_array($this->request->action, ['view', 'edit', 'delete'])){
                if ($Projects->isOwnedBy($projectId, $user['id'])){
                    return true;
                }
            }

            // A moderator of a project can add and edit tickets.
            if (in_array($this->request->action, ['add', 'view', 'edit'])){
                if ($ProjectsUsers->isModeratedBy($projectId, $user['id'])){
                    return true;
                }
            }

            $TicketsUsers = TableRegistry::get('TicketsUsers');

            // Users assigned to tickets can view them.
            if (in_array($this->request->action, ['view'])){
                $ticketId = (int)$this->request->params['pass'][0];
                if ($TicketsUsers->isAssignedTo($ticketId, $user['id'])){
                    return true;
                }
            }
        }

        // Otherwise default to Admin -> true, User -> false
        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

        // If no parameters passed show everything.
        // Else use the status specified
        if ( empty($this->request->params['pass'])) {
            $status = 'All';
        } else {
            $status = $this->request->params['pass'][0];
        }

        // Get title queries from passed parameters.
        $queries = $this->request->params['pass'];
        array_shift($queries);


        // Filter tickets by parameters we got above.
        $this->set('tickets', $this->paginate($this->Tickets
            ->find('byStatus',['status' =>$status])
            ->find('byTitle',['queries' => $queries])));

        $this->set(compact('tickets'));
        $this->set('_serialize', ['tickets']);
    }

    /**
     * View method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Projects', 'Comments', 'Users']
        ]);

        $this->set('ticket', $ticket);
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add($projectId)
    {
        $ticket = $this->Tickets->newEntity();
        if ($this->request->is('post')) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Tickets->Projects->find(
            'list', ['limit' => 200])
            ->where(['id' => $projectId]);
        $this->set('projectId', $projectId);
        $comments = $this->Tickets->Comments->find('list', ['limit' => 200]);
        
        $users = $this->Tickets->Users
            ->find('list', ['limit' => 200])
            ->innerJoinWith(
                'ProjectsUsers', function($q) use( &$projectId){
                    return $q->where(['ProjectsUsers.project_id' => $projectId]);
                }
            );
        
        $this->set(compact('ticket', 'projects', 'comments', 'users'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ticket = $this->Tickets->get($id, [
            'contain' => ['Projects', 'Comments', 'Users']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ticket = $this->Tickets->patchEntity($ticket, $this->request->data);
            if ($this->Tickets->save($ticket)) {
                $this->Flash->success(__('The ticket has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The ticket could not be saved. Please, try again.'));
            }
        }
        $projects = $this->Tickets->Projects->find('list', ['limit' => 200]);
        $comments = $this->Tickets->Comments->find('list', ['limit' => 200]);

        // Get the related project id from the projects_tickets table.
        $projectId = $this->Tickets->ProjectsTickets->find()
            ->where(['ticket_id' => $id])
            ->first()['project_id'];

        // Filter users by if they are involved in a project.
        $users = $this->Tickets->Users
            ->find('list', ['limit' => 200])
            ->innerJoinWith(
                'ProjectsUsers', function($q) use( &$projectId){
                    return $q->where(['ProjectsUsers.project_id' => $projectId]);
                }
            );

        $this->set(compact('ticket', 'projects', 'comments', 'users'));
        $this->set('_serialize', ['ticket']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Ticket id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ticket = $this->Tickets->get($id);
        if ($this->Tickets->delete($ticket)) {
            $this->Flash->success(__('The ticket has been deleted.'));
        } else {
            $this->Flash->error(__('The ticket could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
