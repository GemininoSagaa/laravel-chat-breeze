<script>
    import { Head } from '@inertiajs/svelte';
    import Layout from '@/Layouts/AuthenticatedLayout.vue';
    import Chat from '@/Components/Chat.svelte';
    import FriendsList from '@/Components/FriendsList.svelte';
    
    export let auth;
    export let friends;
    export let groups;
    export let messages;
    export let currentChat;
    export let isGroup;
  </script>
  
  <Head title="Chat" />
  
  <Layout {auth}>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="flex h-[600px]">
            <FriendsList {friends} {groups} {currentChat} {isGroup} />
            <div class="flex-1">
              <Chat 
                {messages} 
                {currentChat} 
                {isGroup} 
                userId={auth.user.id} 
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>