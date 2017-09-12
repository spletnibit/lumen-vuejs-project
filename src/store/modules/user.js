import Vapi from 'vuex-rest-api'

const user = new Vapi({
  baseURL: 'http://ponudbe.dev/api/public',
  state: {
    user: {
      token: localStorage.getItem('token')
    }
  }})
  .post({
    action: 'loginUser',
    property: 'user',
    path: '/login'})
  .post({
    action: 'logoutUser',
    property: 'user',
    path: '/logout'})
.getStore({
  createStateFn: true
})

user.mutations['saveToken'] = (state) => {
  localStorage.setItem('token', state.user.token)
}

user.mutations['logOutUser'] = (state) => {
  localStorage.removeItem('token')
  state.user.token = null
}

export default user
