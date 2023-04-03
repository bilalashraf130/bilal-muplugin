<?php
namespace App\Helpers;


use Exception;
use App\Models\ACTTrackingData;

/**
 * Helper Class for Active Campaign CRM events tracking
 */
class ACTTrackingHelper {

	private $_tracking_action = "act_track";

	private $actClient = null;

	private $api_key = '8a972681b64b963c48eb9343e97610db4458554af17a33ee572c847e9c7db02b71214c38';
	private $event_key = '029bf1578e018bdf549c1e2f4a789620c595d277';
	private $actid = '999724048';
	private $account_name = 'bvtf';
	private $api_version = '3';
	private $tracking_url = 'https://trackcmp.net/event';

	private $base_url = 'https://#account_name.api-us1.com/api/#api_version/';

	private $request_headers = null;

	public function __construct() {
		$this->base_url = str_replace(['#account_name','#api_version'],[$this->account_name, $this->api_version],$this->base_url);
		$this->request_headers = [
			'Api-Token' => $this->api_key,
			'Content-Type' => 'application/json'
		];
		$this->actClient = new \GuzzleHttp\Client();
	}

	/**
	 * Parses the request response into associatve array
	 *
	 * @param $res RequestResponse
	 *
	 * @return mixed
	 */
	public function parseResponse($res) {
		$response = $res->getBody();
		$response = json_decode($response,1);
		return $response;
	}

	/**
	 * Returns all available events in the active campaign
	 *
	 * @return mixed
	 */
	public function getAllEvents() {
		$request = new \GuzzleHttp\Psr7\Request('GET', $this->base_url.'eventTrackingEvents',$this->request_headers);
		$res = $this->actClient->sendAsync($request)->wait();
		$response =  $this->parseResponse($res);
		if(empty($response)) {
			throw new Exception("Error occurred while fetching events from ACT");
		}
		return $response['eventTrackingEvents'];
	}


	/**
	 * Creates the event in active campaig
	 *
	 * @param $eventName
	 *
	 * @return mixed
	 */
	public function createEvent($eventName) {
		$body = json_encode(["eventTrackingEvent"=> ["name"=> $eventName]], JSON_FORCE_OBJECT) ;
		$request = new \GuzzleHttp\Psr7\Request('POST', $this->base_url.'eventTrackingEvents',$this->request_headers,$body);
		$res = $this->actClient->sendAsync($request)->wait();
		$response =  $this->parseResponse($res);
		if(empty($response)) {
			throw new Exception("Creation of Event: {$eventName} has failed ");
		}
		return $response['eventTrackingEvents'];
	}

	/**
	 * Track event against certain contact based on passed email address
	 *
	 * @param $eventName
	 * @param $userEmail
	 * @param $data
	 *
	 * @return mixed
	 */
	public function trackEvent($eventName, $userEmail, $data = null) {
		$data = is_string($data) ? json_encode($data, JSON_FORCE_OBJECT) : $data;
		$options = [
			'multipart' => [
				[
					'name' => 'actid',
					'contents' => $this->actid
				],
				[
					'name' => 'key',
					'contents' => $this->event_key
				],
				[
					'name' => 'event',
					'contents' => $eventName
				],
				[
					'name' => 'eventdata',
					'contents' => $data
				],
				[
					'name' => 'visit',
					'contents' => "{\"email\":\"{$userEmail}\"}"
				]
			]];
		$request = new \GuzzleHttp\Psr7\Request('POST', $this->tracking_url);
		$res = $this->actClient->sendAsync($request,$options)->wait();
		$response =  $this->parseResponse($res);
		if(empty($response) || $response['success'] != 1) {
			throw new Exception("Error occurred while spawning event '{$eventName}' for '{$userEmail}'");
		}
		return $response['message'];
	}

	/**
	 * Finds the contact by email and returns
	 *
	 * @param $email
	 *
	 * @return mixed
	 */
	public function getContactsByEmail($email) {

		try {
			$method = 'contacts?filters[email]='.$email;
			$request = new \GuzzleHttp\Psr7\Request('GET', $this->base_url.$method,$this->request_headers);
			$res = $this->actClient->sendAsync($request)->wait();
		} catch (\GuzzleHttp\Exception\ClientException $e) {

			dd($e);

		}

		$response =  $this->parseResponse($res);
		if(empty($response)) {
			throw new Exception("Error fetching contact with email '{$email}'");
		}
		return $response['contacts'] ?? false;
	}

	/**
	 * Creates the contact record with provided params
	 *
	 * @param $email
	 * @param $firstName
	 * @param $lastName
	 * @param $phone
	 *
	 * @return false|mixed
	 */
	public function createContactRecord($email, $firstName, $lastName, $phone='') {
		$body = json_encode(["contact"=> ["email"=> $email, "firstName"=> $firstName, "lastName"=> $lastName,'phone'=>$phone]], JSON_FORCE_OBJECT) ;
		$request = new \GuzzleHttp\Psr7\Request('POST', $this->base_url.'contacts',$this->request_headers,$body);
		$res = $this->actClient->sendAsync($request)->wait();
		$response =  $this->parseResponse($res);
		if(empty($response)) {
			throw new Exception("Error occurred while creating a contact with email '{$email}'");
		}
		return $response['contact'] ?? false;
	}

	/**
	 * Tracks event in Active Campaign based on order id
	 *
	 * @param $orderId
	 *
	 * @return void
	 */
	public static function createCoursePurchaseEvent($orderId) {
		global $wpdb;
		$boughtProducts = $wpdb->get_results("
		SELECT wp_wc_customer_lookup.customer_id,
		       wp_wc_customer_lookup.user_id,
		       wp_wc_customer_lookup.username,
		       wp_wc_customer_lookup.first_name,
		       wp_wc_customer_lookup.last_name,
		       wp_wc_customer_lookup.email,
		       wp_woocommerce_order_itemmeta.meta_value AS product_id
		FROM   wp_wc_order_stats
		       INNER JOIN wp_wc_customer_lookup
		               ON wp_wc_order_stats.customer_id =
		                  wp_wc_customer_lookup.customer_id
		                  AND wp_wc_order_stats.order_id = {$orderId}
		                  AND wp_wc_order_stats.status = 'wc-completed'
		       INNER JOIN wp_woocommerce_order_items
		               ON wp_wc_order_stats.order_id =
		                  wp_woocommerce_order_items.order_id
		                  AND wp_woocommerce_order_items.order_item_type = 'line_item'
		       INNER JOIN wp_woocommerce_order_itemmeta
		               ON wp_woocommerce_order_items.order_item_id =
		                             wp_woocommerce_order_itemmeta.order_item_id
		                  AND wp_woocommerce_order_itemmeta.meta_key = '_product_id';
	");
		foreach ($boughtProducts as $productInfo) {
			$relatedCourses = get_post_meta( $productInfo->product_id, '_related_course');
			$relatedCourses = reset($relatedCourses);
			foreach ($relatedCourses as $courseId) {
				if(!empty($courseId)) {
					$course = wp_get_single_post( $courseId);
					$eventName = "Course_id_{$courseId}_purchased";
					$eventDescription = "{$course->post_title} is purchased by a exisitng user on members site";
					self::addTrackingInfoAfterTime($eventName, $productInfo->email, $productInfo->first_name,$productInfo->last_name, $eventDescription, 120);
				}
			}
		}
	}

	/**
	 * Static function to be called by action hook to perform tracking event
	 *
	 * @param $eventName
	 * @param $userEmail
	 * @param $firstName
	 * @param $lastName
	 * @param $data
	 *
	 * @return void
	 */
	public static function performTrackingEvent($eventName, $userEmail,$firstName,$lastName, $data) {
		$result = array('status' => 'success', 'message' => 'Event has been spawned');
		try{
			$trackingHelper = new ACTTrackingHelper();
			$contacts = $trackingHelper->getContactsByEmail($userEmail);
			if(empty($contacts) && !empty($userEmail)) {
				$trackingHelper->createContactRecord($userEmail, $firstName, $lastName);
			}
			$result['message'] = $trackingHelper->trackEvent($eventName, $userEmail, $data);
		} catch (\GuzzleHttp\Exception\ClientException $e) {

			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			$error = json_decode($responseBodyAsString);
			$api_error_message = $error->errors[0]->error;

			$test = ACTTrackingData::where('user_email', $userEmail)->update(['api_error_message' => $api_error_message]);

		}
		return $result;
	}

	/**
	 * Static function to be called by action hook
	 * It should be use to schedule the event tracking
	 *
	 * @param $eventName
	 * @param $userEmail
	 * @param $firstName
	 * @param $lastName
	 * @param $data
	 * @param $time
	 *
	 * @return void
	 */
	public static function addTrackingInfoAfterTime($eventName, $userEmail, $firstName,$lastName, $data = null, $time=120) {

		ACTTrackingData::create([
			"event_name" => $eventName,
			"user_email" => $userEmail,
			"first_name" => $firstName,
			"last_name" => $lastName,
			"message" => $data,
			"status" => false
		]);
	}

	/**
	 * Schedule ACT Tracking
	 *
	 * @return void
	 */
	public static function scheduleCronJob() {

		// Add 5 minutes interval
		add_filter( 'cron_schedules', [new \BlackSheepCommunity\Helpers\ACTTrackingHelper(),"act_tracking_cron"] );

		// Schedule an action if it's not already scheduled
		if ( ! wp_next_scheduled( 'act_tracking_cron' ) ) {
			wp_schedule_event( time(), 'every_five_minutes', 'act_tracking_cron' );
		}

		// Schedule an action if it's not already scheduled
		if ( ! wp_next_scheduled( 'act_tracking_cron' ) ) {
			wp_schedule_event( time(), 'daily', 'act_tracking_inactive_2_weeks' );
		}
	}

	public function act_tracking_cron( $schedules ) {
		$schedules['every_five_minutes'] = array(
				'interval'  => 300,
				'display'   => __( 'Every 5 Minutes', 'textdomain' )
		);
		return $schedules;
	}

	/**
	 * Function for ACT tracking
	 *
	 * @return void
	 */
	public static function actTracking() {

		$results = ACTTrackingData::where("status",0)->get()->toArray();
		$count = 0;
		$batches = array_chunk($results, 2,true);
		foreach($batches as $batch) {
			$start_time = microtime(true);
			foreach ($batch as $item) {

				$perform_track = self::performTrackingEvent($item['event_name'], $item['user_email'], $item['first_name'], $item['last_name'], $item['message']);
				if ($perform_track['status'] === 'success') {
					ACTTrackingData::where('event_name', $item['event_name'])
						->where('id', $item['id'])
						->update(['status' => true]);
				}
				$current_time = microtime(true);
				$time_diff = ($current_time - $start_time) * 1000;
				if( $time_diff < 1000 ){
					$sleep_time = (1000 - $time_diff);
					sleep($sleep_time * 1000);
				}

			}
		}
		dd('complete');
//
		//microtime start time

//		foreach($chnks as $batch){
//			$start_time = explode(" ", microtime())[1];
//			//chunk array
//			foreach(){
//				//performa tracking
//				//performaed 2 requests
//			}
//
//			//time for a new batch
//			//Current microtime
//			//Difference between start and end time
//			// <1sec then sleep(difference in 1 sec)
//
//		}
//		foreach($results as $result){
//			$perform_track = self::performTrackingEvent($result->event_name, $result->user_email, $result->first_name, $result->last_name, $result->message);
//
//			if($perform_track['status'] === 'success'){
//				ACTTrackingData::where('event_name', $result->event_name)
//					->where('id', $result->id)
//					->update(['status' => true]);
//				$count++;
//				$end_time = explode(" ", microtime())[1];
//				$difference = $end_time - $start_time;
//				if(($difference < 1000) && $count == 2){
//					sleep((1000 - $difference)/1000);
//					$start_time = explode(" ", microtime())[1];
//					$count = 0;
//				}
//			}
//		}
	}
}
