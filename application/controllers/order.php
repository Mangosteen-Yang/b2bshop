<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//商品控制器
class Order extends Home_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('goods_model');
		$this->load->model('order_model');
	}

	public function index(){
		// $user = $this->session->userdata('user');
		// var_dump($user);
		$order = $this->order_model->get_user_order(1);
		foreach ($order as $k => $v) {
			$goods_order[$k]['goods'] = $this->order_model->get_goods_order($v['order_id']);
			$goods_order[$k]['order'] = $v;
		}
		$data['goods_order'] = $goods_order;
		var_dump($data);
		$this->load->view('order.html', $data);
	}

	public function add() {
		$data['address'] = $this->input->post('address');
		$data['user_name'] = $this->input->post('user_name');
		$data['user_id'] = $this->input->post('user_id');
		$data['postscripts'] = $this->input->post('postscripts');
		$data['phone'] = $this->input->post('phone');
		$data['time'] = date("Y-m-d g:i:s a", time());
		$data['order_amount'] = $this->cart->tatol();		
		if ($data['address']&&$data['phone']&&$data['user_name']){
			redirect('cart/show');
		}
		if($id = $this->order_model->apply_order($data)) {
				$carts = $thi->cart->contents();
				$data = array();
				foreach ($carts as $v) {
					$data['goods_id'] = $v['id'];
					$data['goods_name'] = $v['name'];
					$data['number'] = $v['qty'];
					$data['shop_price'] = $v['price'];
					$data['subtotal'] = $v['subtotal'];
					$data['order_id'] = $id;
				}
				if($id = $this->order_model->apply_goods_order($data)){
					$this->cart->destroy();
					redirect('home');
				} else {
					echo "error: 订单商品插入失败";
				}
		} else {
			echo "error:订单产生失败";
			// redirect('cart/show');
		}

	}

	public function delete_order() {
		$order_id = $this->input->post('order_id');
		$this->order_model->delete($order_id);
		redirect('order/index');
	}

	public function form(){
		$this->load->view('form.html');
	}
}