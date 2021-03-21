<template>
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row mt-5">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3 v-if="$store.state.orders.orders">
              {{ $store.state.orders.orders.total }}
            </h3>
            <h3 v-else>&nbsp;</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="fas fa-shopping-bag"></i>
          </div>
          <a href="#" class="small-box-footer"
            >More info <i class="fas fa-arrow-circle-right"></i
          ></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3 v-if="$store.state.products.products">
              {{ $store.state.products.products.total }}
            </h3>
            <h3 v-else>&nbsp;</h3>
            <p>Products</p>
          </div>
          <div class="icon">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <a href="#" class="small-box-footer"
            >More info <i class="fas fa-arrow-circle-right"></i
          ></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3 v-if="$store.state.suppliers.suppliers">
              {{ $store.state.suppliers.suppliers.total }}
            </h3>
            <h3 v-else>&nbsp;</h3>
            <p>Suppliers</p>
          </div>
          <div class="icon">
            <i class="fas fa-industry"></i>
          </div>
          <a href="#" class="small-box-footer"
            >More info <i class="fas fa-arrow-circle-right"></i
          ></a>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3 v-if="users">{{ users }}</h3>
            <h3 v-else>&nbsp;</h3>

            <p>Registered Users</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
          <a href="#" class="small-box-footer"
            >More info <i class="fas fa-arrow-circle-right"></i
          ></a>
        </div>
      </div>
      <!-- ./col -->
    </div>
    <div class="row justify-content-center">
      <div class="col-md-5">
        <bar-chart
          v-if="!isLoaing"
          :title="'Total products by last 10 suppliers'"
          :yLabelString="'Total Products'"
          :xLabelString="'Suppliers'"
          :chartdata="chartdata"
        />
      </div>
      <div class="col-md-5">
        <bar-chart
          v-if="!isLoaing"
          :title="'Total latest products quantity'"
          :yLabelString="'Quantity'"
          :xLabelString="'Products'"
          :chartdata="chartdata2"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import BarChart from "../charts/BarChart";
import ChartSetting from "../common/charts-settings";
export default {
  components: {
    BarChart,
  },

  data() {
    return {
      chartdata: null,
      chartdata2: null,
      isLoaing: false,
      users: 0,
    };
  },

  computed: {
    ...mapGetters(["getSuppliersReport"]),
  },

  async mounted() {
    console.log("Testing")
    this.isLoaing = true;
    if (!this.$store.state.products.products) {
      this.getProductsAction();
    }

    if (!this.$store.state.orders.orders) {
      this.getOrdersAction();
    }

    if (!this.$store.state.suppliers.suppliers) {
      await this.getSuppliersAction();
    }
    
    await this.setChartData();
    await this.setChartData2();

    this.getUser();

    this.isLoaing = false;
  },

  methods: {
    ...mapActions([
      "getOrdersAction",
      "getProductsAction",
      "getSuppliersAction",
    ]),

    getUser() {
      axios.get("api/users").then((response) => {
        this.users = response.data;
      });
    },

    async setChartData() {
      this.chartdata = {
        labels: this.$store.state.suppliers.suppliers.data.map(
          (data) => data.name
        ),
        datasets: [
          {
            label: "Supplier Products",
            data: this.$store.state.suppliers.suppliers.data.map(
              (data) => data.supplier_products.length
            ),
            borderWidth: 1,
            pointBorderColor: "#2554FF",
            borderColor: ChartSetting.borderColor,
            backgroundColor: ChartSetting.backgroundColor,
          },
        ],
      };
    },

    async setChartData2() {
      this.chartdata2 = {
        labels: this.$store.state.products.products.data.map(
          (data) => data.name
        ),
        datasets: [
          {
            label: "Products Quantity",
            data: this.$store.state.products.products.data.map(
              (data) => data.quantity
            ),
            borderWidth: 1,
            pointBorderColor: "#2554FF",
            borderColor: ChartSetting.borderColor,
            backgroundColor: ChartSetting.backgroundColor,
          },
        ],
      };
    },
  },
};
</script>
