import axios from "axios";

import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

Vue.component('real-map', require('../components/RealMapTable.vue').default);

Vue.prototype.$i18nForDatatable = (function () {
    var locale = {
        'Apply': historyTrans.template.apply,
        'Apply and backup settings to local': historyTrans.template.apply_and_backup_settings_to_local,
        'Clear local settings backup and restore': historyTrans.template.clear_local_settings_backup_and_restore,
        'Using local settings': historyTrans.template.using_local_settings,
        'No Data': historyTrans.template.no_data,
        'Total': historyTrans.template.total,
        ',': historyTrans.template.comma,
        'items / page': historyTrans.template.items_page
    };
  
    return function (srcTxt) {
        return locale[srcTxt];
    };
})();

var tree = new Vue({
    el: '#app',
    data() {
        return {
            landscape: [],
            data: {},
            trans: trans,
            intervalfunction: null,
            isLoading: false,
            current: user_id,
            temp: '',
            currentUser: '',
            showTable: true
        }
    },
    components: {
        Loading
    },
    mounted: function () {
        this.current = user_id;
        this.temp = this.current;
        this.intervalfunction = setInterval(this.loadAlerts, 10000);
    },
    methods: {
        updateSteps(data) {
            this.currentUser = data;
            this.temp = data.parent_id;
        },
        updateCurrent(data) {
            this.current = data;
        },
        updateValue(data) {
            this.temp = data.account_id;
        },
        search_upward() {
            if (this.current == user_id) {
                return;
            }
            if (this.currentUser.parent_id == 0 ) {
                return;
            } else {
                this.current = this.currentUser.parent_id;
            }
        },
        show_table() {
            this.showTable = !this.showTable;
        }
    },
    beforeDestroy: function(){
        clearInterval(this.intervalfunction);
    },

})