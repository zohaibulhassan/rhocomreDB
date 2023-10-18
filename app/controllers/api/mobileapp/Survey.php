<?php defined('BASEPATH') or exit('No direct script access allowed');
class Survey extends App_Controller{
    function __construct(){
        parent::__construct();
    }
    public function list(){
        $this->db->select('*');
        $this->db->from('survey_questions');
        $this->db->where('status',1);
        $q = $this->db->get();
        $this->data['survey'] = $q->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
    public function attempt(){
        $customer = $this->input->post('customer');
        $user_id = $this->input->post('user_id');
        $questions = $this->input->post('questions');
        if($user_id != "" && $customer != "" && $questions != ""){
            $questions = json_decode($questions);
            foreach($questions as $question){
                $this->db->select('*');
                $this->db->from('survey_questions');
                $this->db->where('id',$question->question_id);
                $q = $this->db->get();
                if($q->num_rows() > 0){
                    $data = $q->result()[0];
                    $insert['customer_id'] = $customer;
                    $insert['question'] = $data->question;
                    $insert['type'] = $data->type;
                    $insert['options'] = $data->options;
                    $insert['answer'] = $question->answer;
                    $insert['created_by'] = $user_id;
                    $this->db->insert('survey_client_attempts',$insert);
                }
            }
            if(count($questions)>0){
                $this->data['code_status'] = true;
                $this->data['message'] = "Success!";
            }
            else{
                $m = "Questions Not Found!";
                $this->data['message'] = $m;
                $this->data['error_code'] = '004';
            }
        }
        else{
            $m = "Required Query Parameter Null!";
            $this->data['message'] = $m;
            $this->data['error_code'] = '003';
        }
        $this->responsedata();
    }
    public function attempt_list(){
        $customer = $this->input->post('customer');
        $date = $this->input->post('date');
        $this->db->select('*');
        $this->db->from('survey_client_attempts');
        $this->db->where('customer_id',$customer);
        $this->db->where('date_format(created_at,"%Y-%m-%d")',date('Y-m-d',strtotime($date)));
        $q = $this->db->get();
        $this->data['atempts'] = $q->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }


    public function servey_list_group(){
        $user_id = $this->input->post('user_id');
        $this->db->select('date_format(sma_survey_client_attempts.created_at,"%Y-%m-%d") as date, IFNULL(sma_companies.name,"") as customer_name, IFNULL(sma_companies.id,"") as customer_id');
        $this->db->from('sma_survey_client_attempts');
        $this->db->join('sma_companies','sma_companies.id = sma_survey_client_attempts.customer_id','left');
        $this->db->where('sma_survey_client_attempts.created_by',$user_id);
        $this->db->group_by('date_format(sma_survey_client_attempts.created_at,"%Y-%m-%d")');
        $q = $this->db->get();
        $this->data['atempts'] = $q->result();
        $this->data['code_status'] = true;
        $this->data['message'] = "Success!";
        $this->responsedata();
    }
}

