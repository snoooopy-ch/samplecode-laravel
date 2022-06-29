<template>
    <datatable v-bind="$data" id="bonus-history"/>
</template>


<script>
    import DataTable from 'vue2-datatable-component';
    import CustomCell from './Advance/td-CustomCell.vue';

    export default {
        name: 'History',
        props: ['trans'],
        components: {
            'datatable': DataTable,
            CustomCell
        },
        data: () => ({
            columns: [],
            data: [],
            total: 0,
            query: {},
            lang: {},
            currencies: [],
          }),
        created: function() {
            this.lang = JSON.parse(this.trans);
            this.columns = [
                { title: this.lang.table.date, field: 'created_at', thClass:'text-center word-keep', tdClass: 'text-center', tdComp: 'CustomCell' },
                { title: this.lang.table.amount, field: 'total_bet', thClass:'text-center word-keep', tdClass: 'text-end', tdComp: 'CustomCell' },
                { title: this.lang.table.tree, field: 'tree', thClass:'text-center word-keep', tdClass: 'text-center', tdComp: 'CustomCell' },
                { title: this.lang.table.basic, field: 'basic', thClass:'text-center word-keep', tdClass: 'text-end', tdComp: 'CustomCell' },
                { title: this.lang.table.level, field: 'level', thClass:'text-center word-keep', tdClass: 'text-center word-keep', tdComp: 'CustomCell' },
                { title: this.lang.table.rate, field: 'bonus_rate', thClass:'text-center word-keep', tdClass: 'text-end', tdComp: 'CustomCell' },
                { title: this.lang.table.bonus, field: 'bonus', thClass:'text-center word-keep', tdClass: 'text-end', tdComp: 'CustomCell' },
                { title: this.lang.table.sum, field: 'sum', thClass:'text-center word-keep', tdClass: 'text-end', tdComp: 'CustomCell' },
            ];
            this.loadCurrencies();
        },
        mounted: function () {
        },
        methods: {
            async loadCurrencies() {
                let self = this;
                axios.get(window.Laravel.baseUrl + '/currency')
                    .then( (response) => {
                        self.currencies = response.data;
                    })
                    .catch( (error) => {
                        console.log(error);
                    })
                    .then( (data) => {
                    })
            }
        },
        watch: {
            query: {
                handler (query) {
                    let page = this.query.offset / this.query.limit + 1;
                    this.query.page = page;
                    axios.get(window.Laravel.baseUrl + '/history/list/bonus', {
                            params: this.query
                        })
                        .then( (data) => {
                            this.data = data.data.data.map( obj => {
                                return {
                                    ...obj, 
                                    created_at: obj['settle_month'].replace('-', '/'),
                                    total_bet: this.lang.table.jpy + this.$options.filters.number2format(obj['total_bet'], 0),
                                    tree: this.lang.realtree + '<br>' + this.lang.binarytree,
                                    basic: this.lang.table.jpy + this.$options.filters.number2format(obj['data'][0]['basic_bonus'], 0) + '<br>' + this.lang.table.jpy + this.$options.filters.number2format(obj['data'][1]['basic_bonus'], 0),
                                    bonus: this.lang.table.jpy + this.$options.filters.number2format(obj['data'][0]['bonus'], 0) + '<br>' + this.lang.table.jpy + this.$options.filters.number2format(obj['data'][1]['bonus'], 0),
                                    level: obj['data'][0]['level'] + '<br>' + obj['data'][1]['level'], 
                                    bonus_rate: this.$options.filters.number2format(obj['data'][0]['bonus_rate'], 0) + '%' + '<br>' + this.$options.filters.number2format(obj['data'][1]['bonus_rate'], 0) + '%',
                                    sum: obj['sum'],
                                }
                            })
                            this.total = data.data.total;
                    })
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    #bonus-history div[name="SimpleTable"] {
        overflow-x: auto;
    }
</style>