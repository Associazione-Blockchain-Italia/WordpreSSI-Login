<?php

use Inc\Providers\Trinsic\Controllers\Impl\Messaging\TrinsicChatController;

$messages = [];

if ( isset( $_GET['connectionId'] ) ) {
	$messages = TrinsicChatController::getAllMessages( $_GET['connectionId'] );
}
?>

<div class="wrap">
    <div class="trinsic-chat-title">Trinsic Chat</div>
    <div class="trinsic-chat-connectionId">
        <form id="trinsic-chat-form" action="" method="get">
            <input type="hidden" name="page" value="trinsic_chat">
            <label>
                Connection ID:
                <select name="connectionId" class="trinsic-chat-select">
                    <option value="" <?php
					if ( ! isset( $_GET['connectionId'] ) ) {
						echo "selected";
					}
					?> >Choose a Connection ID
                    </option>
					<?php
					$connections = TrinsicChatController::getAllConnections();
					foreach ( $connections as $connection ) {
						if ( $connection['state'] == 'Connected' ) {
							?>
                            <option value="<?php
							echo $connection['connectionId']; ?>"
								<?php
								if ( $_GET['connectionId'] == $connection['connectionId'] ) {
									echo "selected";
								} ?>
                            ><?php
								echo $connection['name'] . " - " . substr( $connection['connectionId'],
										0,
										10 ) . "..."; ?></option>
							<?php
						}
					}
					?>
                </select>
            </label>
            <input type="submit" hidden/>
        </form>
    </div>
    <div class="trinsic-chat-messages">
		<?php
		if ( isset( $_GET['connectionId'] ) ) {
			foreach ( $messages as $message ) {
				?>
                <div class="trinsic-chat-<?php
				if ( $message['direction'] == "Incoming" ) {
					echo "from";
				} else {
					echo "to";
				} ?>">
					<?php
					echo $message['text'] ?>
                </div>
				<?php
			}
		} ?>
    </div>
    <div class="trinsic-chat-message">
        <label>
            <textarea class="trinsic-chat-textarea" name="message"></textarea>
        </label>
        <button class="trinsic-chat-send" type="submit" onclick="sendNewMessage()">Send</button>
    </div>
</div>
