<template>
<div>
    <el-button @click="showInviteDialog = true">Invite User</el-button>
    <el-table ref="filterTable" :data="users" style="width: 100%">
        <el-table-column prop="first_name" label="First Name" width="180">
        </el-table-column>
        <el-table-column prop="last_name" label="Last Name" width="180">
        </el-table-column>
        <el-table-column
            prop="role"
            label="Role"
            width="100"
            :filters="[
                { text: 'Admin', value: 'admin' },
                { text: 'Customer', value: 'customer' }
            ]"
            :filter-method="filterTag"
            filter-placement="bottom-end"
        >
            <template slot-scope="scope">
                <el-tag
                    :type="scope.row.role === 'admin' ? 'primary' : 'success'"
                    disable-transitions
                    >{{ scope.row.role }}</el-tag
                >
            </template>
        </el-table-column>
        <el-table-column
            prop="signup_link"
            label="Active"
            width="100"
        >
            <template slot-scope="scope">
                <el-tag
                    :type="!scope.row.signup_link ? 'primary' : 'success'"
                    disable-transitions
                    >{{ !scope.row.signup_link ? 'Active' : 'Pending' }}</el-tag
                >
            </template>
        </el-table-column>
    </el-table>
        <!-- Invite Dialog -->
    <el-dialog :visible.sync="showInviteDialog" size="small" :close-on-click-modal="false" :show-close="false"
      :lock-scroll="true" :modal-append-to-body="false" :append-to-body="true">
      <div>
        <h3>Invite New User</h3>
      </div>
      <div v-loading="loading">
        <el-form ref="user" :model="userForm" label-width="120px" label-position="top">
            <el-form-item required label="First Name" prop="first_name" :error="errors.first_name">
                <el-input placeholder="John" v-model="userForm.first_name" autofocus></el-input>
            </el-form-item>
            <el-form-item required label="Last Name" prop="last_name" :error="errors.last_name">
                <el-input placeholder="Doe" v-model="userForm.last_name" autofocus></el-input>
            </el-form-item>
            <el-form-item required label="Email" :error="errors.email">
                <el-input placeholder="user@test.co.za" v-model="userForm.email"></el-input>
            </el-form-item>
             <el-radio-group v-model="userForm.type">
                <el-radio-button label="admin">Admin</el-radio-button>
                <el-radio-button label="customer">Customer</el-radio-button>
            </el-radio-group>
        </el-form>
      </div>
      <span slot="footer">
        <el-button type="text" @click="showInviteDialog = false">Cancel</el-button>
        <el-button type="primary" :disabled="!userForm.last_name || !userForm.first_name || !userForm.email || !userForm.type" @click="inviteUser()">Send
          Invite</el-button>
      </span>
    </el-dialog>
</div>
</template>

<script>
export default {
    props: ["users"],
    mounted() {
        console.log("Component mounted.");
        console.log(this.users);
    },
    data(){
        return {
            showInviteDialog:false,
            userForm:{
                first_name:'',
                last_name:'',
                email:'',
                type:''
            },
            loading:false,
            errors:{}
        }
    },
    methods:{
        filterTag(value, row) {
            return row.role === value;
        },
              /**
       * Submit form
       * @return string
       */
      inviteUser() {
        this.loading = true;
        axios.post('/admin/user/invite', this.userForm)
          .then(function (response) {
            this.loading = false;
            this.showInviteDialog = false;
            this.userForm = {
              email: '',
              first_name: '',
              last_name: '',
            };
            this.$notify.success({
              title: 'Success',
              message: 'Invite Sent',
              offset: 20
            });
          }.bind(this))
          .catch(function (error) {
            this.loading = false;
            this.errors = {};

            _.map(error.response.data.errors, (item, key) => {
              this.errors[key] = item[0];
            });
            console.log(error.response.data);
            console.log(this.errors)
          }.bind(this));

      },
    }
};
</script>
