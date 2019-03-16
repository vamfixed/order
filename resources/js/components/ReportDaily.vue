<template>
    <div class="">
        <div class="form-group">
            <label for="cboReport">Select Month-Year</label>
            <select v-model="month" class="form-control" @change="update">
                <option :value="m.m + '-' + m.year" v-for="(m, index) in months" :key="index">{{ m.month }}-{{ m.year }}</option>
            </select>
        </div>

        <table class="table table-hover table-striped table-bordered">
            <tr>
                <th>Date</th>
                <th>Amount</th>
            </tr>
            <tr v-for="(order, index) in orders" :key="index">
                <td>{{ order.date }}</td>
                <td>{{ order.total }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>{{ total }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                month: null,
                orders: null,
                months: []
            }
        },
        computed: {
            total() {
                var t = 0;
                if (this.orders) {
                    this.orders.forEach(element => {
                        t += parseFloat(element.total);
                    });
                }
                return t.toFixed(2);
            }
        },
        methods: {
            update() {
                this.$Progress.start();
                axios.get('/api/report/daily/' + this.month)
                .then(res => {
                    this.orders = res.data;
                    this.$Progress.finish();
                })
                .catch(err => {
                    console.log(err);
                    this.$Progress.fail();
                })
            }
        },
        created() {
            axios.post('/api/report/getMonthYear')
            .then(res => {
                this.months = res.data;
            })
            .catch(error => {
                console.log(error);
            });
        },
    }

</script>
