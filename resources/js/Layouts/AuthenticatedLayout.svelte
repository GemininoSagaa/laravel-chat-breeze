<script>
    import ApplicationLogo from '@/Components/ApplicationLogo.svelte';
    import Dropdown from '@/Components/Dropdown.svelte';
    import DropdownLink from '@/Components/DropdownLink.svelte';
    import NavLink from '@/Components/NavLink.svelte';
    import ResponsiveNavLink from '@/Components/ResponsiveNavLink.svelte';
    import { page } from '$app/stores';
    import { onMount } from 'svelte';
    
    let showingNavigationDropdown = false;
    let authUser = null;
  
    onMount(() => {
      $page.subscribe(({ props }) => {
        authUser = props?.auth?.user || null;
      });
    });
  </script>
  
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
          <div class="flex">
            <div class="flex shrink-0 items-center">
              <a href="/dashboard">
                <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
              </a>
            </div>
            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
              <NavLink href="/dashboard" active={page.url === '/dashboard'}>Dashboard</NavLink>
            </div>
          </div>
  
          <div class="hidden sm:ms-6 sm:flex sm:items-center">
            <div class="relative ms-3">
              <Dropdown align="right" width="48">
                <div slot="trigger">
                  <span class="inline-flex rounded-md">
                    <button class="inline-flex items-center bg-white px-3 py-2 text-sm font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                      {authUser ? authUser.name : ''}
                      <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                      </svg>
                    </button>
                  </span>
                </div>
                <div slot="content">
                  <DropdownLink href="/profile/edit">Profile</DropdownLink>
                  <DropdownLink href="/logout" method="post">Log Out</DropdownLink>
                </div>
              </Dropdown>
            </div>
          </div>
  
          <div class="-me-2 flex items-center sm:hidden">
            <button on:click={() => showingNavigationDropdown = !showingNavigationDropdown} class="p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900">
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                {#if showingNavigationDropdown}
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                {:else}
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                {/if}
              </svg>
            </button>
          </div>
        </div>
      </div>
  
      {#if showingNavigationDropdown}
        <div class="sm:hidden">
          <ResponsiveNavLink href="/dashboard" active={page.url === '/dashboard'}>Dashboard</ResponsiveNavLink>
          <div class="border-t border-gray-200 dark:border-gray-600 pb-1 pt-4">
            <div class="px-4">
              <div class="text-base font-medium text-gray-800 dark:text-gray-200">{authUser?.name}</div>
              <div class="text-sm font-medium text-gray-500">{authUser?.email}</div>
            </div>
            <ResponsiveNavLink href="/profile/edit">Profile</ResponsiveNavLink>
            <ResponsiveNavLink href="/logout" method="post">Log Out</ResponsiveNavLink>
          </div>
        </div>
      {/if}
    </nav>
  
    {#if $$slots.header}
      <header class="bg-white shadow dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>
    {/if}
  
    <main>
      <slot />
    </main>
  </div>