import router from '@/router';
import axios from 'axios';
import { defineStore } from 'pinia'
import Swal from 'sweetalert2';

export const useAuthStore = defineStore('auth',
	{
		state: ()=>({
			isAuthenticated: false,
			isInitialized: false
		}),

		actions: {
			/**
			 * Requests the server for the login of the user and updates the stored variables accordingly and returns the errors if any
			 * 
			 * @param {Object} userParam 
			 * @param {string} token 
			 */
			async login(email, password){
				const body = {
					email: email,
					password: password
				};

				let errors = {};

				await axios.get('/sanctum/csrf-cookie');

				await axios.post(`/spa/login`, body).then((res) => {
					Swal.fire({
						title: "Success",
						text: "Successfully logged in!",
						icon: "success"
					});

					this.isAuthenticated = true;
					localStorage.setItem('token', res.data.token);

					router.push({ name: 'home' });
				}).catch(error => {
					errors = error.response.data.errors;
				});

				return errors;
			},

			/**
			 * Logs the user out
			 */
			async logout(){
				await axios.get('/sanctum/csrf-cookie');

				await axios.post(`/spa/logout`).then(()=>{
					this.isAuthenticated = false;
					
					localStorage.removeItem('token');

					router.push({ name: 'home' });
				});
			},

			/**
			 * Initializes the store
			 */
			async initialize() {
				if(!this.isInitialized)
				{
					await axios.get(`/spa/user`).then(() => {
						this.isAuthenticated = true;
					}) .catch(() => {
						this.isAuthenticated = false;
					});
					this.isInitialized=true;
				}
			}
		},
	}
)