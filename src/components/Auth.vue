<template>
  <form class="row" style="margin-top: 20px;" @submit.prevent="onLogin">
    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <h3>Prijava</h3>
    </div>
    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <at-input
        type="email"
        placeholder="Vnesi e-mail"
        :status="getStatus('email')"
        v-model.trim="login.email"
        icon="at-sign"></at-input>
    </div>

    <div class="col-md-12 col-md-offset-6" style="margin-bottom: 20px;">
      <at-input
        placeholder="Vnesi geslo"
        v-model.trim="login.password"
        :status="getStatus('password')"
        type="password"
        icon="lock"></at-input>
    </div>

    <div class="col-md-12 col-md-offset-6">
      <at-button
        type="submit"
        icon="icon-log-in">Prijavi se</at-button>
    </div>
  </form>
</template>

<script type="text/babel">
  import { mapState, mapActions, mapMutations } from 'vuex'

  export default {
    name: 'Auth',
    data () {
      return {
        login: {
          email: null,
          password: null
        }
      }
    },
    methods: {
      onLogin () {
        var self = this

        this.loginUser({
          data: {
            email: this.login.email,
            password: this.login.password
          }
        }).then((res) => {
          this.saveToken({
            token: res.data.token
          })
          self.$Notify.success('Prijava uspešna.')
          this.$router.push({ path: '/offers/' })
        }).catch((e) => {
          try {
            if (e.response.data[0] === 'user_not_found') {
              self.$Message.error('Uporabnik ne obstaja ali pa so podatki napačni.')
            }

            if (typeof e.response.data['password'] !== 'undefined') {
              self.$Message.error(e.response.data['password'][0])
            }

            setTimeout(() => {
              if (typeof e.response.data['email'] !== 'undefined') {
                self.$Message.error(e.response.data['email'][0])
              }
            }, 250)
          } catch (ee) {
            console.log('error', ee)
          }
        })
      },
      getStatus (key) {
        if (typeof this.error_user !== 'undefined' && this.error_user !== null) {
          if (this.error_user.hasOwnProperty('response')) {
            if (this.error_user.response.data.hasOwnProperty(key)) {
              return 'error'
            }
          }
        }
        return ''
      },
      ...mapActions([
        'loginUser'
      ]),
      ...mapMutations([
        'saveToken'
      ])
    },
    computed: {
      ...mapState({
        user: state => state.user.user,
        pending_user: state => state.user.pending.user,
        error_user: state => state.user.error.user
      })
    }
  }
</script>
