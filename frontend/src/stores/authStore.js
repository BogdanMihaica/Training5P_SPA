import { defineStore } from 'pinia'

export const useAuthStore = defineStore('authStore', () => {
  	let user = null;
	let isAuthenticated = false;

	function login(user){
		this.user = user
		this.isAuthenticated = true
	}

	function logout(){
		this.user = null
		this.isAuthenticated = false
	}

	return {user, isAuthenticated, login, logout}
})