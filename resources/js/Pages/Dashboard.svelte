<script>
    import { Head, router } from '@inertiajs/svelte';
    import Layout from '@/Layouts/AuthenticatedLayout.vue';
    
    export let auth;
    
    function goToChat() {
      router.visit('/chat');
    }
  </script>
  
  <Head title="Dashboard" />
  
  <Layout {auth}>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <h1 class="text-2xl font-semibold mb-4">¡Bienvenido a Chat App!</h1>
            <p class="mb-6">Esta aplicación te permite chatear con amigos y crear grupos de conversación.</p>
            
            <div class="flex space-x-4">
              <button 
                on:click={goToChat} 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md"
              >
                Ir al Chat
              </button>
              
              <a 
                href="/friends" 
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md inline-block"
              >
                Gestionar Amigos
              </a>
              
              <a 
                href="/groups" 
                class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-md inline-block"
              >
                Gestionar Grupos
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>