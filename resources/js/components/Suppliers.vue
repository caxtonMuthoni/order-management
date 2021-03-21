<template>
  <div class="container-fluid">
    <div class="card mt-2">
      <div class="card-header card-header-content">
        <h3 class="indigo card-heading">
          <i class="fas fa-industry"></i> Registered Suppliers
        </h3>
        <a href="" @click.prevent="showModal" class="btn btn-sm btn-success"
          ><i class="fas fa-plus mr-2"></i>Add New Supplier</a
        >
      </div>
      <table class="table table-striped table-bordered">
        <thead>
          <th>#</th>
          <th>Supplier</th>
          <th>Supplier Products</th>
          <th>Created At</th>
          <th>Supplier Products</th>
          <th>Delete</th>
        </thead>
        <tbody v-if="$store.state.suppliers.suppliers">
          <tr
            v-for="(supplier, index) in $store.state.suppliers.suppliers.data"
            :key="index"
          >
            <td>{{ index + 1 }}</td>
            <td class="table-nowrap">{{ supplier.name }}</td>
            <td>
              <span
                class="chip"
                v-for="(detail, index) in supplier.supplier_products"
                :key="index"
              >
                {{ detail.product.name }},
              </span>
            </td>
            <td class="table-nowrap">{{ supplier.created_at | formatDate }}</td>
            <td>
              <router-link
                :to="`/supplier/${supplier.id}`"
                class="btn btn-sm btn-primary"
                ><i class="fas fa-eye"></i
              ></router-link>
            </td>
            <td>
              <a
                href=""
                @click.prevent="deleteSupplier(supplier.id)"
                class="btn btn-sm btn-danger"
                ><i class="fas fa-trash"></i
              ></a>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <th>#</th>
          <th>Product</th>
          <th>Description</th>
          <th>Quantity</th>
          <th>Supplier Products</th>
          <th>Delete</th>
        </tfoot>
      </table>
      <div class="card-footer" v-if="$store.state.suppliers.suppliers">
        <pagination
          :data="$store.state.suppliers.suppliers"
          :limit="5"
          @pagination-change-page="getNextSuppliersAction"
          align="right"
        ></pagination>
      </div>
    </div>

    <!-- Modal -->
    <div
      class="modal fade"
      id="supplier-modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="modelTitleId"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title indigo">
              <i class="fas fa-plus"></i> Adding new Supplier
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form @submit.prevent="addSupplier()">
            <div class="modal-body">
              <div class="form-group row">
                <label
                  for="colFormLabelLg"
                  class="col-sm-3 col-form-label col-form-label-lg"
                  >Supplier Name</label
                >
                <div class="col-sm-9">
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="form-control form-control-lg"
                    id="name"
                    placeholder="Product Name"
                    name="name"
                    :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-danger"
                  data-dismiss="modal"
                >
                  Close
                </button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import { Form } from "vform";
export default {
  data() {
    return {
      form: new Form({
        name: "",
      }),
    };
  },

  mounted() {
    this.getSuppliersAction();
  },

  methods: {
    ...mapActions(["getSuppliersAction", "getNextSuppliersAction"]),

    addSupplier() {
      this.$Progress.start();
      this.form
        .post("/api/suppliers/add")
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.hideModal()
            this.$router.push({ name: 'supplier-products', params: { id: response.data.supplier.id } })
            this.$swal.fire({
              icon: "success",
              title: "Sucess !",
              text: response.data.message,
            });
          } else {
            this.$Progress.fail();
            this.$swal.fire({
              icon: "error",
              title: "Failed !!!",
              text: response.data.message,
            });
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$swal.fire({
            icon: "error",
            title: "Failed !!!",
            text: error.message,
          });
        });
    },

    deleteSupplier(id) {
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.delete(id);
          }
        });
    },

    delete(id) {
      this.$Progress.start();
      axios
        .delete(`/api/suppliers/delete/${id}`)
        .then((response) => {
          if (response.data.status) {
            this.$Progress.finish();
            this.getSuppliersAction();
            this.$swal.fire({
              icon: "success",
              title: "Sucess !",
              text: response.data.message,
            });
          } else {
            this.$Progress.fail();
            this.$swal.fire({
              icon: "error",
              title: "Failed !!!",
              text: response.data.message,
            });
          }
        })
        .catch((error) => {
          this.$Progress.fail();
          this.$swal.fire({
            icon: "error",
            title: "Failed !!!",
            text: error.message,
          });
        });
    },

    showModal() {
      $("#supplier-modal").modal({
        backdrop: "static",
        keyboard: false,
      });
      $("#supplier-modal").modal("show");
    },

    hideModal() {
      $("#supplier-modal").modal("hide");
    },
  },
};
</script>