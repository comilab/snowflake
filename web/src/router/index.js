import Vue from 'vue'
import Router from 'vue-router'
import Login from '@/pages/admin/Login'
import PostList from '@/pages/admin/PostList'
import PostNew from '@/pages/admin/PostNew'
import PostDetail from '@/pages/admin/PostDetail'
import PostComment from '@/pages/admin/PostComment'
import PageList from '@/pages/admin/PageList'
import PageNew from '@/pages/admin/PageNew'
import PageDetail from '@/pages/admin/PageDetail'
import UploadList from '@/pages/admin/UploadList'
import UploadNew from '@/pages/admin/UploadNew'
import UploadDetail from '@/pages/admin/UploadDetail'
import Setting from '@/pages/admin/Setting'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      redirect: '/posts'
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/posts',
      name: 'PostList',
      component: PostList,
      props: route => {
        if (route.query.page) {
          route.query.page = parseInt(route.query.page)
        }
        return route
      }
    },
    {
      path: '/posts/new',
      name: 'PostNew',
      component: PostNew
    },
    {
      path: '/posts/:id',
      name: 'PostDetail',
      component: PostDetail,
      props: route => {
        if (route.params.id) {
          route.params.id = parseInt(route.params.id)
        }
        return route
      }
    },
    {
      path: '/posts/:post_id/comments',
      name: 'PostComment',
      component: PostComment,
      props: route => {
        if (route.params.post_id) {
          route.params.post_id = parseInt(route.params.post_id)
        }
        return route
      }
    },
    {
      path: '/pages',
      name: 'PageList',
      component: PageList,
      props: route => {
        if (route.query.page) {
          route.query.page = parseInt(route.query.page)
        }
        return route
      }
    },
    {
      path: '/pages/new',
      name: 'PageNew',
      component: PageNew
    },
    {
      path: '/pages/:id',
      name: 'PageDetail',
      component: PageDetail,
      props: route => {
        if (route.params.id) {
          route.params.id = parseInt(route.params.id)
        }
        return route
      }
    },
    {
      path: '/uploads',
      name: 'UploadList',
      component: UploadList,
      props: route => {
        if (route.query.page) {
          route.query.page = parseInt(route.query.page)
        }
        return route
      }
    },
    {
      path: '/uploads/new',
      name: 'UploadNew',
      component: UploadNew
    },
    {
      path: '/uploads/:id',
      name: 'UploadDetail',
      component: UploadDetail,
      props: route => {
        if (route.params.id) {
          route.params.id = parseInt(route.params.id)
        }
        return route
      }
    },
    {
      path: '/settings',
      name: 'Setting',
      component: Setting
    }
  ]
})
