<script>
    import { Head } from '@inertiajs/svelte';
    import Layout from '@/Layouts/AuthenticatedLayout.vue';
    import { router } from '@inertiajs/svelte';
    
    export let auth;
    export let friends;
    export let pendingRequests;
    export let users;
    
    function sendRequest(userId) {
      router.post(`/friends/${userId}`);
    }
    
    function acceptRequest(friendshipId) {
      router.put(`/friends/${friendshipId}/accept`);
    }
    
    function rejectRequest(friendshipId) {
      router.delete(`/friends/${friendshipId}`);
    }
  </script>
  
  <Head title="Friends" />
  
  <Layout {auth}>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Friends</h2>
            <a href="/chat" class="text-blue-500 hover:underline">Go to Chat</a>
          </div>
          
          <!-- Friend Requests -->
          {#if pendingRequests.length > 0}
            <div class="mb-8">
              <h3 class="text-lg font-medium mb-4">Friend Requests</h3>
              <div class="space-y-2">
                {#each pendingRequests as request}
                  <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>{request.user.name}</div>
                    <div class="flex space-x-2">
                      <button 
                        on:click={() => acceptRequest(request.id)}
                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                      >
                        Accept
                      </button>
                      <button 
                        on:click={() => rejectRequest(request.id)}
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                      >
                        Reject
                      </button>
                    </div>
                  </div>
                {/each}
              </div>
            </div>
          {/if}
          
          <!-- Friends List -->
          <div class="mb-8">
            <h3 class="text-lg font-medium mb-4">Your Friends</h3>
            {#if friends.length > 0}
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {#each friends as friend}
                  <div class="p-4 border rounded-lg">
                    <div class="font-medium">{friend.name}</div>
                    <a 
                      href={`/chat/${friend.id}`}
                      class="text-sm text-blue-500 hover:underline"
                    >
                      Message
                    </a>
                  </div>
                {/each}
              </div>
            {:else}
              <p class="text-gray-500">You don't have any friends yet.</p>
            {/if}
          </div>
          
          <!-- Add Friends -->
          <div>
            <h3 class="text-lg font-medium mb-4">Add Friends</h3>
            {#if users.length > 0}
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {#each users as user}
                  <div class="p-4 border rounded-lg flex justify-between items-center">
                    <div>{user.name}</div>
                    <button 
                      on:click={() => sendRequest(user.id)}
                      class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600"
                    >
                      Add
                    </button>
                  </div>
                {/each}
              </div>
            {:else}
              <p class="text-gray-500">No users available to add.</p>
            {/if}
          </div>
        </div>
      </div>
    </div>
  </Layout>