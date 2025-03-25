<?php
    use Illuminate\Support\Str;
?>

<div class="container-fluid h-100 d-flex">
    <!-- Inbox Section -->
    <div class="col-3 bg-light p-3 border-right" style="height: 100vh; overflow-y: auto;">
        <h2>Inbox</h2>
        <input type="text" wire:model.live="searchTerm" class="form-control mb-3" placeholder="Search users to chat...">

        <!--[if BLOCK]><![endif]--><?php if($searchTerm): ?>
            <div class="search-results">
                <h4>Search Results:</h4>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $searchResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div wire:click="selectUser(<?php echo e($user['id']); ?>)"
                        class="btn btn-light btn-block text-left mb-2 d-flex align-items-center" data-toggle="tooltip"
                        data-placement="right" title="Chat with <?php echo e($user['name']); ?>">
                        <!-- Placeholder for user photo with initials -->
                        <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px; background-color: <?php echo e($this->getRandomColor($user->id)); ?>;">
                            <span class="text-white font-weight-bold"><?php echo e($this->getInitials($user['name'])); ?></span>
                        </div>
                        <span><?php echo e($user['name']); ?></span>
                        <!-- Display unread message count with a green badge if there are unread messages -->
                        <!--[if BLOCK]><![endif]--><?php if($this->getUnreadCount($user['id']) > 0): ?>
                            <span class="badge badge-success ml-auto">
                                <?php echo e($this->getUnreadCount($user['id'])); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No users found</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php else: ?>
            <div class="inbox-users">
                <h4>Recent Messages</h4>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $recentMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div wire:click="selectUser(<?php echo e($user->id); ?>)"
                                class="btn btn-link text-left d-flex align-items-center" style="width: 100%;"
                                data-toggle="tooltip" data-placement="right" title="Chat with <?php echo e($user->name); ?>">
                                <!-- Placeholder for user photo with initials -->
                                <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; background-color: <?php echo e($this->getRandomColor($user->id)); ?>;">
                                    <span
                                        class="text-white font-weight-bold"><?php echo e($this->getInitials($user->name)); ?></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                    <span><?php echo e($user->name); ?></span>
                                    <small class="text-muted">
                                        <!--[if BLOCK]><![endif]--><?php if($user->latestMessage): ?>
                                            <?php echo e(optional(optional($user->latestMessage)->created_at)->diffForHumans()); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">
                                <!--[if BLOCK]><![endif]--><?php if($user->latestMessage): ?>
                                    <?php echo e(Str::limit($user->latestMessage->message, 50)); ?>

                                <?php else: ?>
                                    No messages yet
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </small>
                            <!-- Display unread message count with a green badge if there are unread messages -->
                            <!--[if BLOCK]><![endif]--><?php if($this->getUnreadCount($user->id) > 0): ?>
                                <div class="d-flex justify-content-end">
                                    <span class="badge badge-success">
                                        <?php echo e($this->getUnreadCount($user->id)); ?> Unread
                                    </span>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No recent messages</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <h4 class="mt-4">All Messages</h4>
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $allMessages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="card mb-2">
                        <div class="card-body p-2">
                            <div wire:click="selectUser(<?php echo e($user->id); ?>)"
                                class="btn btn-link text-left d-flex align-items-center" style="width: 100%;"
                                data-toggle="tooltip" data-placement="right" title="Chat with <?php echo e($user->name); ?>">
                                <!-- Placeholder for user photo with initials -->
                                <div class="rounded-circle mr-2 d-flex align-items-center justify-content-center"
                                    style="width: 40px; height: 40px; background-color: <?php echo e($this->getRandomColor($user->id)); ?>;">
                                    <span
                                        class="text-white font-weight-bold"><?php echo e($this->getInitials($user->name)); ?></span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center flex-grow-1">
                                    <span><?php echo e($user->name); ?></span>
                                    <small class="text-muted">
                                        <!--[if BLOCK]><![endif]--><?php if($user->latestMessage): ?>
                                            <?php echo e(optional(optional($user->latestMessage)->created_at)->diffForHumans()); ?>

                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">
                                <!--[if BLOCK]><![endif]--><?php if($user->latestMessage): ?>
                                    <?php echo e(Str::limit($user->latestMessage->message, 50)); ?>

                                <?php else: ?>
                                    No messages yet
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </small>
                            <!-- Display unread message count with a green badge if there are unread messages -->
                            <!--[if BLOCK]><![endif]--><?php if($this->getUnreadCount($user->id) > 0): ?>
                                <div class="d-flex justify-content-end">
                                    <span class="badge badge-success">
                                        <?php echo e($this->getUnreadCount($user->id)); ?> Unread
                                    </span>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p>No messages yet</p>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    </div>

    <!-- Chat Section -->
    <!--[if BLOCK]><![endif]--><?php if($view == 'chat'): ?>
        <div class="col-9 p-3 d-flex flex-column">
            <!-- Back to Inbox Button -->
            

            <!-- Chat Header -->
            <h2
                class="bg-white py-2 px-4 md:px-6 lg:px-8 text-lg md:text-xl font-semibold shadow-sm border-b border-gray-300 sticky top-12 z-10">
                Chat with <?php echo e($selectedUser->name); ?>

            </h2>


            <!-- Chat Container -->
            <div class="flex-grow-1 overflow-auto border p-3 mb-3 bg-white" style="max-height: calc(100vh - 200px);">
                <?php
                    $lastDate = null;
                ?>

                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $messageDate = \Carbon\Carbon::parse($message['created_at']);
                        $formattedDate = $messageDate->toDateString();
                        $currentDate = \Carbon\Carbon::today()->toDateString();
                        $yesterdayDate = \Carbon\Carbon::yesterday()->toDateString();
                    ?>

                    <!--[if BLOCK]><![endif]--><?php if($formattedDate != $lastDate): ?>
                        <div class="bg-light py-2 px-3 text-center">
                            <!--[if BLOCK]><![endif]--><?php if($formattedDate == $currentDate): ?>
                                <h5 class="text-muted">Today</h5>
                            <?php elseif($formattedDate == $yesterdayDate): ?>
                                <h5 class="text-muted">Yesterday</h5>
                            <?php else: ?>
                                <h5 class="text-muted"><?php echo e($messageDate->format('F j, Y')); ?></h5>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <?php
                            $lastDate = $formattedDate;
                        ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


                    <!-- Message Bubble -->
                    <div class="d-flex align-items-start mb-3"
                        style="flex-direction: <?php echo e($message['sender_id'] == auth()->id() ? 'row-reverse' : 'row'); ?>">
                        <!-- Avatar -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center mr-2"
                            style="width: 40px; height: 40px; background-color: <?php echo e($this->getRandomColor($message['sender_id'])); ?>;">
                            <span class="text-white font-weight-bold">
                                <!--[if BLOCK]><![endif]--><?php if(isset($message['sender']) && isset($message['sender']['name'])): ?>
                                    <?php echo e($this->getInitials($message['sender']['name'])); ?>

                                <?php else: ?>
                                    <?php echo e($this->getInitials('Unknown')); ?>

                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </span>
                        </div>

                        <!-- Message and Menu -->
                        <div class="w-100"
                            style="max-width: 50%; text-align: left; display: flex; flex-direction: column; justify-content: flex-start;">
                            <!-- Menu Button -->
                            <div class="dropdown float-right">
                                <button class="btn btn-link p-0" type="button" data-toggle="dropdown">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <!--[if BLOCK]><![endif]--><?php if($message['sender_id'] == auth()->id()): ?>
                                        <a class="dropdown-item" href="#"
                                            wire:click="editMessage(<?php echo e($message['id']); ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    <?php else: ?>
                                        <a class="dropdown-item disabled" href="#"
                                            onclick="event.preventDefault();">
                                            <i class="fas fa-reply"></i> Reply
                                        </a>
                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <a class="dropdown-item" href="#"
                                        wire:click="deleteMessage(<?php echo e($message['id']); ?>)">
                                        <i class="fas fa-trash"></i> Delete for Me
                                    </a>
                                    <a class="dropdown-item disabled" href="#" onclick="event.preventDefault();">
                                        <i class="fas fa-archive"></i> Archive
                                    </a>
                                    <a class="dropdown-item disabled" href="#" onclick="event.preventDefault();">
                                        <i class="fas fa-share-alt"></i> Share
                                    </a>
                                </div>
                            </div>

                            <!-- Message Body -->
                            <div class="p-3 mb-2"
                                style="border-radius: 15px; background-color: <?php echo e($message['sender_id'] == auth()->id() ? '#e0f7fa' : '#f1f8e9'); ?>; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);">
                                <!--[if BLOCK]><![endif]--><?php if($editReply && $message['id'] == $messageBeingRepliedTo->id): ?>
                                    <div>
                                        <input type="text" class="form-control" wire:model="editedMessage"
                                            value="<?php echo e($message['message']); ?>">
                                        <button wire:click="updateMessage(<?php echo e($message['id']); ?>)"
                                            class="btn btn-success btn-sm mt-2" title="Update Message">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button wire:click="cancelEdit" class="btn btn-danger btn-sm mt-2"
                                            title="Cancel Edit">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <div class="message-content">
                                        <p class="mb-1 text-justify"
                                            style="font-size: 1.1rem; line-height: 1.5; color: #333;">
                                            <?php echo e($message['message']); ?></p>
                                        <small class="text-muted" style="font-size: 0.9rem;">
                                            <?php echo e(\Carbon\Carbon::parse($message['created_at'])->diffForHumans()); ?>

                                        </small>
                                    </div>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                <!-- Typing Indicator -->
                <!--[if BLOCK]><![endif]--><?php if($otherUserTyping): ?>
                    <div class="text-muted">
                        <?php echo e($selectedUser->name); ?> is typing...
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            <!-- Message Input -->
            <div style="position: relative; display: flex; align-items: center;">
                <!-- Textarea -->
                <textarea wire:model.lazy="message" rows="3" class="form-control" placeholder="Type your message..."
                    style="padding-right: 50px; resize: none;" wire:keydown="startTyping" wire:keyup="stopTyping">
                </textarea>

                <!-- Send Button -->
                
                    <button wire:click="sendMessage" class="btn btn-primary"
                        style="position: absolute; right: 10px; bottom: 10px; padding: 5px 10px; border-radius: 50%;"
                        data-toggle="tooltip" data-placement="top" title="Send Message">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                
            </div>

        </div>
    <?php else: ?>
        <!-- Placeholder for other views like outbox or drafts -->
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->


</div>
<?php /**PATH C:\projects\MbukuErp\resources\views/livewire/message-manager.blade.php ENDPATH**/ ?>