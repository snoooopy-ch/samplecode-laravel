import axios from "axios";

Vue.component('binary-tree', require('../components/TreeComponent.vue').default);
Vue.component('tree-dialog', require('../components/TreeDialog.vue').default);
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';

var tree = new Vue({
    name: 'BinaryTree',
    el: '#app',
    data() {
        return {
            landscape: [],
            data: {},
            trans: trans,
            intervalfunction: null,
            isLoading: false,
        }
    },
    components: {
        Loading
    },
    mounted: function () {
        this.intervalfunction = setInterval(this.loadAlerts, 10000);
        this.loadNodes();
    },
    methods: {
        async loadNodes() {
            let self = this;
            axios.get(window.Laravel.baseUrl + '/tree/binary')
                .then( (response) => {
                    self.data = response.data;
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then( (data) => {
                });
        },
        async loadUpward(id) {
            if (id == window.Laravel.user) {
                return;
            }
            let self = this;
            axios.get(window.Laravel.baseUrl + '/tree/upward/' + id)
                .then( (response) => {
                    self.data = response.data;
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then( (data) => {
                });
        },
        async loadDownward(id) {
            let self = this;
            axios.get(window.Laravel.baseUrl + '/tree/downward/' + id)
                .then( (response) => {
                    self.data = response.data;
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then( (data) => {
                });
        },
        clickNode: function(node){
            // if (node.count == '') {
            //     this.loadUpward(node.user_id);
            // } else {
            //     this.loadDownward(node.user_id);
            // }
        }
    },
    beforeDestroy: function(){
        clearInterval(this.intervalfunction);
    },

})