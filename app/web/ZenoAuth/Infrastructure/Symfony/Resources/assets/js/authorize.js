/*
 * This file is part of the Zeno Auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Vue from 'vue';
import VueResource from 'vue-resource';

Vue.use(VueResource);

Vue.component('ballfall-spinner', require('./components/ball-fall-spinner.component.vue').default);

new Vue({
    el: '#authorize',
    data: {
        authorizing: false,
        error: ''
    },
    methods: {
        authorize() {
            this.error = '';
            this.authorizing = true;

            const action = this.$refs['authorize-form'].action;
            const csrf = this.$refs['csrf'].value;

            this.$http.post(action, { _csrf_token: csrf }).then(
                response => {
                    window.location = response.body.redirect_uri;
                },
                response => {
                    if (response.body.hasOwnProperty('error')) {
                        this.error = response.body.error;
                    }
                }
            ).finally(() => this.authorizing = false);

            return;
        },

        cancel(uri) {
            window.location.href = uri;
        }
    }
});
