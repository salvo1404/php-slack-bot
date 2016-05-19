<?php
/**
 * Created by IntelliJ IDEA.
 * User: balzanos
 * Date: 5/19/16
 * Time: 2:55 PM
 */

require 'vendor/autoload.php';
use PhpSlackBot\Bot;

// Custom command
class MyCommand extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('ciao');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'Ciao bello !' );
	}

}

// Custom command
class Eugenesfault extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('who-broke-the-site');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'Eugene');
	}

}

// Custom command
class Context extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('context');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, 'message = ' . json_encode( $message ) . ', channel = ' . $context['channel'] );
	}

}

// Custom command
class MessageInfo extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('info');
	}

	protected function execute($message, $context) {
		$this->send($this->getCurrentChannel(), null, json_encode( $message ) );
	}

}

// Custom command
class Who extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('who');
	}

	protected function execute($message, $context) {
		$question = strtoupper( $message['text'] );

		if( $question  === 'WHO AM I') {
			$this->send($this->getCurrentChannel(), null, $this->getUserNameFromUserId( $this->getCurrentUser() ) );
		} elseif ( $question === 'WHO BROKE THE SITE' ) {
			$this->send($this->getCurrentChannel(), null, 'Eugene :D' );
		} else {
			$this->send($this->getCurrentChannel(), null, "Don't know man" );
		}
	}

}

// Custom command
class What extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('what');
	}

	protected function execute($message, $context) {
		$question = strtoupper( $message['text'] );

		if( $question  === 'WHAT DO YOU WANT') {
			$this->send($this->getCurrentChannel(), null, 'I want to sleep' );
		} elseif ( $question === "WHAT'S UP" ) {
			$this->send($this->getCurrentChannel(), null, "Hey buddy, let's go for a beer" );
		} else {
			$this->send($this->getCurrentChannel(), null, "Too many questions..Sorry I'm hungover" );
		}
	}

}

// Custom command
class Params extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('params');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $message['text'] );
	}

}

// Custom command
class Help extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		$this->setName('help');
	}

	protected function execute($message, $context) {

		$this->send($this->getCurrentChannel(), null, $message['text'] );
	}

}

// This special command executes on all events
class SumoLiveTail extends \PhpSlackBot\Command\BaseCommand {

	protected function configure() {
		// We don't have to configure a command name in this case
	}

	protected function execute($data, $context) {
		if ($data['type'] == 'message') {
			$channel = $this->getChannelNameFromChannelId($data['channel']);
			$username = $this->getUserNameFromUserId($data['user']);
			$message = $username.' from '.($channel ? $channel : 'DIRECT MESSAGE').' : '.$data['text'].PHP_EOL;
			// echo $message;
			$this->send( $channel, null, $message );
		}
	}

}

class SumoWebhook extends \PhpSlackBot\Webhook\BaseWebhook {

	protected function configure() {
		$this->setName('sumo');
	}

	protected function execute($payload, $context) {
		// $payload['channel'] = $this->getChannelIdFromChannelName($payload['channel']);

		$this->send( $payload['channel'], null, $payload['text'] );
		// $this->getClient()->send(json_encode($payload));
	}

}



$bot = new Bot();
$bot->setToken('token'); // Get your token here https://my.slack.com/services/new/bot
$bot->loadCommand( new MyCommand() );
$bot->loadCommand( new Eugenesfault() );
$bot->loadCommand( new Context() );
$bot->loadCommand( new MessageInfo() );
$bot->loadCommand( new Who() );
$bot->loadCommand( new What() );
$bot->loadCommand( new Params() );
$bot->loadWebhook( new SumoWebhook() );
$bot->enableWebserver( 8082, 'secret' );
// $bot->loadCatchAllCommand( new SumoLiveTail() );
$bot->run();