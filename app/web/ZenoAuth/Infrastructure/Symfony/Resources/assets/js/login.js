/*
 * This file is part of the Zeno Auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Vue from 'vue';
import VeeValidate from 'vee-validate';
import VueResource from 'vue-resource';

Vue.use(VeeValidate);
Vue.use(VueResource);

Vue.component('ballfall-spinner', require('./components/ball-fall-spinner.component.vue').default);

new Vue({
    el: '#login',
    data: {
        user: {
            username: '',
            password: ''
        },
        signing: false,
        error: ''
    },
    methods: {
        signin() {
            this.error = '';
            this.$validator.validateAll().then((result) => {
                if (result) {
                    const action = this.$refs['login-form'].action;
                    const csrf = this.$refs['csrf'].value;

                    this.signing = true;

                    this.$http.post(action, Object.assign(this.user, { _csrf_token: csrf }), { emulateJSON: true }).then(
                        response => {
                            window.location.href = '/';
                        },
                        response => {
                            if (response.body.hasOwnProperty('error')) {
                                this.error = response.body.error;
                            }
                        }
                    ).finally(() => this.signing = false);

                    return;
                }
            });
        }
    }
});
