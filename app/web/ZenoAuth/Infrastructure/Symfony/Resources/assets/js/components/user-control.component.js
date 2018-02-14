/*
 * This file is part of the Zeno Auth package.
 *
 * (c) 2018 Borobudur <http://borobudur.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import Vue from 'vue';

/**
 * @author      Iqbal Maulana <iq.bluejack@gmail.com>
 * @created     07/02/18
 */
new Vue({
    el: '#user-control',
    data: {
        active: false
    },
    created() {
        window.document.addEventListener('click', (el) => {
            let found = false;

            el.path.forEach((el) => {
                if (el === this.$el) {
                    found = true;
                }
            });

            if (!found) {
                this.active = false;
            }
        });
    }
});
