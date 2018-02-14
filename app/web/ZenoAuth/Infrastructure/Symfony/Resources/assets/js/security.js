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

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     08/02/18
 */
Vue.use(VeeValidate);
Vue.use(VueResource);

Vue.component('ballfall-spinner', require('./components/ball-fall-spinner.component.vue').default);

new Vue({
    el: '#security',
    data: {
        current_password: '',
        password: '',
        confirm_password: '',
        changing: false,
        error: '',
        success: ''
    },
    methods: {
        changePassword() {
            this.error = '';
            this.$validator.validateAll().then((result) => {
                if (result) {
                    const action = this.$refs['security-form'].action;
                    const csrf = this.$refs['csrf'].value;

                    this.changing = true;

                    this.$http.post(action, { password: this.password, current_password: this.current_password, _csrf_token: csrf }).then(
                        response => {
                            this.success = 'Your password has been updated';
                        },
                        response => {
                            if (response.body.hasOwnProperty('error')) {
                                this.error = response.body.error;
                            }
                        }
                    ).finally(() => this.changing = false);

                    return;
                }
            });
        }
    }
});
