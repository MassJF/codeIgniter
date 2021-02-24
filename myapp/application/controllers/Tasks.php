<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tasks extends CI_Controller
{
    public function display($task_id)
    {
        $data['project_id'] = $this->task_model->get_task_project_id($task_id);
        $data['project_name'] = $this->task_model->get_project_name($data['project_id']);

        $data['task'] = $this->task_model->get_task($task_id);
        $data['main_view'] = "tasks/display";
        $this->load->view('layouts/main', $data);
    }

    // your new methods go here
    public function create($project_id)
    {
        $this->form_validation->set_rules('task_name', 'Task Name', 'trim|required'); 
        $this->form_validation->set_rules('task_body', 'Task Description', 'trim|required'); 
        $this->form_validation->set_rules('due_date', 'Due Date', 'trim|required');

        if ($this->form_validation->run() == false) { 
            $data['main_view'] = 'tasks/create_task'; 
            $this->load->view('layouts/main', $data);
        } else {
            $data = array(
                'project_id' => $project_id, 
                'task_name' => $this->input->post('task_name'), 
                'task_body' => $this->input->post('task_body'), 
                'due_date' => $this->input->post('due_date')
            );

            if ($this->task_model->create_task($data)) { 
                $this->session->set_flashdata('task_created', 'Your Task has been created');
                redirect('projects/display/' . $project_id ,'refresh'); 
            }
        }
    }

    public function edit($task_id)
    {
        $this->form_validation->set_rules('task_name', 'Task Name', 'trim|required'); 
        $this->form_validation->set_rules('task_body', 'Task Description', 'trim|required'); 
        $this->form_validation->set_rules('due_date', 'Due Date', 'trim|required');

        if ($this->form_validation->run() == false) { 
            //restore existing info
            $data['the_task'] = $this->task_model->get_task($task_id);

            $data['main_view'] = 'tasks/edit_task'; 
            $this->load->view('layouts/main', $data);
        } else {
            $data = array(
                'project_id' => $this->task_model->get_task_project_id($task_id), 
                'task_name' => $this->input->post('task_name'), 
                'task_body' => $this->input->post('task_body'), 
                'due_date' => $this->input->post('due_date')
            );

            if ($this->task_model->update_task($task_id, $data)) { 
                $this->session->set_flashdata('task_updated', 'Your Task has been updated');
                redirect('projects/display/' . $this->task_model->get_task_project_id($task_id) ,'refresh'); 
            }
        }
    }
	
    public function delete($project_id, $task_id)
    {
        $this->task_model->delete_task($task_id);

        $this->session->set_flashdata('task_deleted', 'Your task has been deleted');
        redirect("projects/display/" . $project_id ,'refresh');
    }
}
