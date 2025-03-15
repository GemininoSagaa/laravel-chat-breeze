<script>
    import { onMount, onDestroy } from 'svelte';
    
    export let messages = [];
    export let currentChat = null;
    export let isGroup = false;
    export let userId;
    
    let newMessage = "";
    let messageContainer;
    let typingTimeout;
    let isTyping = {};
    
    $: if (messageContainer) {
      scrollToBottom();
    }
    
    $: chatId = isGroup ? currentChat?.id : currentChat?.id;
    $: channelName = isGroup ? `group.${chatId}` : `chat.${userId}.${chatId}`;
    $: channelNameReverse = isGroup ? `group.${chatId}` : `chat.${chatId}.${userId}`;
    
    let echoChannel;
    let echoChannelReverse;
    
    onMount(() => {
      if (currentChat) {
        subscribeToChannels();
      }
      
      scrollToBottom();
    });
    
    onDestroy(() => {
      if (echoChannel) {
        echoChannel.leave();
      }
      if (echoChannelReverse) {
        echoChannelReverse.leave();
      }
      clearTimeout(typingTimeout);
    });
    
    $: if (currentChat) {
      subscribeToChannels();
      scrollToBottom();
    }
    
    function subscribeToChannels() {
      if (echoChannel) {
        echoChannel.leave();
      }
      if (echoChannelReverse) {
        echoChannelReverse.leave();
      }
      
      if (isGroup) {
        echoChannel = window.Echo.join(channelName)
          .listen('MessageSent', (e) => {
            messages = [...messages, e.message];
            scrollToBottom();
          })
          .listenForWhisper('typing', (e) => {
            isTyping[e.user.id] = true;
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
              isTyping[e.user.id] = false;
            }, 3000);
          });
      } else {
        echoChannel = window.Echo.private(channelName)
          .listen('MessageSent', (e) => {
            messages = [...messages, e.message];
            scrollToBottom();
          });
        
        echoChannelReverse = window.Echo.private(channelNameReverse)
          .listen('UserTyping', (e) => {
            isTyping[e.user.id] = true;
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
              isTyping[e.user.id] = false;
            }, 3000);
          });
      }
    }
    
    function scrollToBottom() {
      setTimeout(() => {
        if (messageContainer) {
          messageContainer.scrollTop = messageContainer.scrollHeight;
        }
      }, 50);
    }
    
    function sendMessage() {
      if (!newMessage.trim()) return;
      
      axios.post('/messages', {
        content: newMessage,
        receiver_id: isGroup ? null : currentChat?.id,
        group_id: isGroup ? currentChat?.id : null
      }).then(response => {
        newMessage = "";
      });
    }
    
    function handleKeydown() {
      // Notificar que el usuario está escribiendo
      if (isGroup) {
        echoChannel.whisper('typing', {
          user: {
            id: userId,
            name: 'You'
          }
        });
      } else {
        axios.post('/typing', {
          receiver_id: isGroup ? null : currentChat?.id,
          group_id: isGroup ? currentChat?.id : null
        });
      }
    }
  </script>
  
  <div class="flex flex-col h-full">
    {#if currentChat}
      <div class="bg-gray-200 p-4 border-b">
        <h2 class="text-xl">{currentChat.name}</h2>
      </div>
      
      <div class="flex-1 overflow-y-auto p-4" bind:this={messageContainer}>
        {#each messages as message}
          <div class="mb-4 {message.sender_id === userId ? 'text-right' : ''}">
            <div class="inline-block p-2 rounded-lg {message.sender_id === userId ? 'bg-blue-500 text-white' : 'bg-gray-300'}">
              {#if isGroup && message.sender_id !== userId}
                <div class="text-xs font-bold">{message.sender.name}</div>
              {/if}
              {message.content}
            </div>
            <div class="text-xs text-gray-500 mt-1">
              {new Date(message.created_at).toLocaleTimeString()}
            </div>
          </div>
        {/each}
      </div>
      
      <div class="p-2">
        {#each Object.entries(isTyping) as [typingUserId, isUserTyping]}
          {#if isUserTyping && typingUserId != userId}
            <div class="text-xs text-gray-500 italic mb-1">
              Typing...
            </div>
          {/if}
        {/each}
      </div>
      
      <div class="bg-gray-100 p-4 border-t">
        <form on:submit|preventDefault={sendMessage} class="flex">
          <input
            type="text"
            bind:value={newMessage}
            on:keydown={handleKeydown}
            class="flex-1 border rounded-l px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Type your message..."
          />
          <button 
            type="submit" 
            class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            Send
          </button>
        </form>
      </div>
    {:else}
      <div class="flex items-center justify-center h-full text-gray-500">
        Select a chat to start messaging
      </div>
    {/if}
  </div>