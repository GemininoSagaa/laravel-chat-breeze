<script>
    import { Head } from '@inertiajs/svelte';
    import Layout from '@/Layouts/AuthenticatedLayout.svelte';
    
    export let auth;
    export let groups;
  </script>
  
  <Head title="Groups" />
  
  <Layout {auth}>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Your Groups</h2>
            <div>
              <a 
                href="/chat" 
                class="text-blue-500 hover:underline mr-4"
              >
                Go to Chat
              </a>
              <a 
                href="/groups/create" 
                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
              >
                Create New Group
              </a>
            </div>
          </div>
          
          {#if groups.length > 0}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {#each groups as group}
                <div class="border rounded-lg p-4">
                  <h3 class="font-medium text-lg">{group.name}</h3>
                  <p class="text-sm text-gray-500 mb-4">{group.users ? group.users.length : 0} members</p>
                  <a 
                    href={`/chat/group/${group.id}`}
                    class="text-blue-500 hover:underline"
                  >
                    Open Chat
                  </a>
                </div>
              {/each}
            </div>
          {:else}
            <p class="text-gray-500">You are not a member of any groups yet.</p>
          {/if}
        </div>
      </div>
    </div>
  </Layout>