<script>
    export let friends = [];
    export let groups = [];
    export let currentChat = null;
    export let isGroup = false;
    
    function selectChat(chat, group = false) {
      window.location.href = group 
        ? `/chat/group/${chat.id}` 
        : `/chat/${chat.id}`;
    }
  </script>
  
  <div class="bg-gray-100 w-64 h-full overflow-y-auto border-r">
    <div class="p-4 border-b">
      <h2 class="font-bold text-lg">Chats</h2>
      <div class="mt-2 flex space-x-2">
        <a href="/friends" class="text-sm text-blue-500 hover:underline">Friends</a>
        <a href="/groups" class="text-sm text-blue-500 hover:underline">Groups</a>
        <a href="/groups/create" class="text-sm text-blue-500 hover:underline">New Group</a>
      </div>
    </div>
    
    {#if friends.length > 0 || groups.length > 0}
      <div class="p-2">
        <h3 class="font-semibold text-sm text-gray-500 mb-2">Friends</h3>
        {#each friends as friend}
          <!-- svelte-ignore a11y_click_events_have_key_events -->
          <!-- svelte-ignore a11y_no_static_element_interactions -->
          <div 
            class="p-2 hover:bg-gray-200 cursor-pointer rounded-md mb-1 {!isGroup && currentChat?.id === friend.id ? 'bg-gray-200' : ''}"
            on:click={() => selectChat(friend, false)}
          >
            <div class="font-semibold">{friend.name}</div>
          </div>
        {/each}
        
        {#if groups.length > 0}
          <h3 class="font-semibold text-sm text-gray-500 mt-4 mb-2">Groups</h3>
          {#each groups as group}
            <!-- svelte-ignore a11y_click_events_have_key_events -->
            <!-- svelte-ignore a11y_no_static_element_interactions -->
            <div 
              class="p-2 hover:bg-gray-200 cursor-pointer rounded-md mb-1 {isGroup && currentChat?.id === group.id ? 'bg-gray-200' : ''}"
              on:click={() => selectChat(group, true)}
            >
              <div class="font-semibold">{group.name}</div>
              <div class="text-xs text-gray-500">{group.users ? group.users.length : 0} members</div>
            </div>
          {/each}
        {/if}
      </div>
    {:else}
      <div class="p-4 text-center text-gray-500">
        No friends or groups yet.
      </div>
    {/if}
  </div>