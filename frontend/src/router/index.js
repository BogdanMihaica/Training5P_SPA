import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CartView from '@/views/CartView.vue'
import LoginView from '@/views/LoginView.vue'
import ProductsDashboardView from '@/views/ProductsDashboardView.vue'
import OrdersDashboardView from '@/views/OrdersDashboardView.vue'
import OrderView from '@/views/OrderView.vue'
import ProductManageView from '@/views/ProductManageView.vue'
import { useAuthStore } from '@/stores/authStore'
import NotFound from '@/components/NotFound/NotFound.vue'

const router = createRouter({
	history: createWebHistory(import.meta.env.BASE_URL),
	routes: [
		{
			path: '/',
			name: 'home',
			component: HomeView,
		},
		{
			path: '/cart',
			name: 'cart',
			component: CartView,
		},
		{
			path: '/login',
			name: 'login',
			component: LoginView,
		},
		{
			path: '/products',
			name: 'products',
			component: ProductsDashboardView,
		},
		{
			path: '/orders',
			name: 'orders',
			component: OrdersDashboardView,
		},
		{
			path: '/order/:id',
			name: 'order',
			component: OrderView,
		},
		{
			path: '/product/upload',
			name: 'upload',
			component: ProductManageView,
		},
		{
			path: '/product/:id/edit',
			name: 'product',
			component: ProductManageView,
		},
		{
			path: "/:catchAll(.*)",
			component: NotFound
		}
	],
})

router.beforeEach(async (to, from, next) => {
	const authStore = useAuthStore();
	const privateRoutes = ['products', 'product', 'orders', 'order', 'upload', 'login'];

	if (!authStore.isInitialized) {
		await authStore.initialize();
	}

	if(privateRoutes.includes(to.name)) {
		if(to.name === 'login' && authStore.isAuthenticated) {
			next({ name: 'products' });
		} else if (to.name !== 'login' && !authStore.isAuthenticated ) {
			next({ name: 'login' });
		} else {
			next();
		}
	} else {
		next();
	}
});
export default router
