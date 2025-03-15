<script>
    import { Head } from '@inertiajs/svelte';
    import Layout from '@/Layouts/AuthenticatedLayout.vue';
    import { router } from '@inertiajs/svelte';
    
    export let auth;
    export let friends;
    
    let name = '';
    let selectedFriends = [];
    
    function toggleFriend(friendId) {
      if (selectedFriends.includes(friendId)) {
        selectedFriends = selectedFriends.filter(id => id !== friendId);
      } else {
        selectedFriends = [...selectedFriends, friendId];
      }
    }
    
    function createGroup() {
      router.post('/groups', {
        name,
        members: selectedFriends
      });
    }
  </script>
  
  <Head title="Create Group" />
  
  <Layout {auth}>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <div class="mb-6">
            <h2 class="text-xl font-semibold">Create New Group</h2>
          </div>
          
          <form on:submit|preventDefault={createGroup}>
            <div class="mb-6">
              <label for="name" class="block mb-2 text-sm font-medium">Group Name</label>
              <input
                type="text"
                id="name"
                bind:value={name}
                class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                required
              />
            </div>
            
            <div class="mb-6">
              <!-- svelte-ignore a11y_label_has_associated_control -->
              <label class="block mb-2 text-sm font-medium">Select Friends</label>
              {#if friends.length > 0}
                <div class="space-y-2 max-h-60 overflow-y-auto p-2 border rounded-md">
                  {#each friends as friend}
                    <div class="flex items-center">
                      <input
                        type="checkbox"
                        id={`friend-${friend.id}`}
                        value={friend.id}
                        checked={selectedFriends.includes(friend.id)}
                        on:change={() => toggleFriend(friend.id)}
                        class="mr-2"
                      />
                      <label for={`friend-${friend.id}`}>{friend.name}</label>
                    </div>
                  {/each}
                </div>
              {:else}
                <p class="text-gray-500">You need to add friends first.</p>
              {/if}
            </div>
            
            <div class="flex justify-end">
              <a 
                href="/groups" 
                class="px-4 py-2 mr-2 border rounded-md hover:bg-gray-100"
              >
                Cancel
              </a>
              <button 
                type="submit" 
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
                disabled={!name || selectedFriends.length === 0}
              >
                Create Group
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </Layout>