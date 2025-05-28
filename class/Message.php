<?php

/**
 * Description of Message
 *
 * @author official
 */
class Message
{

    public $id;
    public $status;
    public $description;

    public function __construct($id)
    {
        if ($id) {

            $query = "SELECT `id`,`status`,`description` FROM `message` WHERE `id`=" . $id;

            $db = new Database();

            $result = mysqli_fetch_array($db->readQuery($query));

            $this->id = $result['id'];
            $this->status = $result['status'];
            $this->description = $result['description'];

            return $result;
        }
    }

    public function showMessagesByIDs($ids = [])
    {
        if (empty($ids))
            return;

        $db = new Database();
        $idList = implode(',', array_map('intval', $ids));
        $query = "SELECT `status`, `description` FROM `message` WHERE `id` IN ($idList)";
        $result = $db->readQuery($query);

        // Store messages in array for counting
        $messages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $messages[] = $row;
        }

        $totalMessages = count($messages);
        if ($totalMessages === 0)
            return;

        echo '<div class="position-relative">';

        // Show more/less toggle
        if ($totalMessages > 2) {
            echo '
            <div class="text-end mb-2">
                <a href="#" id="toggle-messages" class="text-primary small">2 of ' . $totalMessages . ' click all messages</a>
            </div>';
        }

        echo '<div id="message-container">';
        foreach ($messages as $index => $msg) {
            $hiddenClass = ($index >= 2) ? 'd-none extra-message' : '';
            echo '
            <div class="row ' . $hiddenClass . '">
                <div class="col-md-12">
                    <div class="alert alert-' . $msg['status'] . ' alert-dismissible fade show" role="alert">
                        ' . $msg['description'] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            </div>';
        }
        echo '</div>'; // end #message-container
        echo '</div>'; // end wrapper
    }

    public function showCustomMessages($messages = [], $status = 'info')
    {
        if (empty($messages))
            return;

        $totalMessages = count($messages);

        echo '<div class="position-relative">';

        // Show count if more than 2 messages
        if ($totalMessages > 1) {
            echo '
        <div class="text-end mb-2">
            <a href="#" id="toggle-messages" class="text-primary small">2 of ' . $totalMessages . ' click all messages</a>
        </div>';
        }

        echo '<div id="message-container">';
        foreach ($messages as $index => $messageText) {
            $hiddenClass = ($index >= 1) ? 'd-none extra-message' : '';
            echo '
        <div class="row ' . $hiddenClass . '">
            <div class="col-md-12">
                <div class="alert alert-' . $status . ' alert-dismissible fade show" role="alert">
                    ' . $messageText . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>';
        }
        echo '</div>'; // end message-container
        echo '</div>'; // end wrapper
    }


}
