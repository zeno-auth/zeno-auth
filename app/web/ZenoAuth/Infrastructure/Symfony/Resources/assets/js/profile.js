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
    el: '#profile',
    data: {
        user: {
            email: '',
            name: ''
        },
        error: '',
        success: '',
        updating: false
    },
    methods: {
        update() {
            this.error = '';
            this.$validator.validateAll().then((result) => {
                if (result) {
                    const action = this.$refs['profile-form'].action;
                    const csrf = this.$refs['csrf'].value;

                    this.updating = true;

                    this.$http.post(action, Object.assign(this.user, { _csrf_token: csrf })).then(
                        response => {
                            this.email = response.body.email;
                            this.name = response.body.name;

                            this.success = 'Your profile has been updated';
                        },
                        response => {
                            if (response.body.hasOwnProperty('error')) {
                                this.error = response.body.error;
                            }
                        }
                    ).finally(() => this.updating = false);

                    return;
                }
            });
        }
    },
    mounted() {
        this.user.email = this.$refs.email.dataset.value;
        this.user.name = this.$refs.name.dataset.value;
    }
});
