import axios from "axios";

Vue.component('home-tree', require('../components/HomeTreeComponent.vue').default);
Vue.component('home-real', require('../components/RealTreeComponent.vue').default);

var tree = new Vue({
    el: '#app',
    data() {
        return {
            landscape: [],
            data: {},
            realTree: {},
            trans: trans,
            intervalfunction: null,
        }
    },
    mounted: function () {
        this.intervalfunction = setInterval(this.loadAlerts, 10000);
        this.loadRealTree();
        this.loadNodes();
    },
    methods: {
        async loadNodes() {
            let self = this;
            axios.get(window.Laravel.baseUrl + '/home/tree')
                .then( (response) => {
                    self.data = response.data;
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then( (data) => {
                });
        },
        async loadRealTree() {
            let self = this;
            axios.get(window.Laravel.baseUrl + '/home/real')
                .then( (response) => {
                    self.realTree = response.data;
                })
                .catch( (error) => {
                    console.log(error);
                })
                .then( (data) => {
                });
        },
        showAlliance: function() {
            $('#alliance').slideToggle();
        }
    },
    beforeDestroy: function(){
        clearInterval(this.intervalfunction);
    },

})