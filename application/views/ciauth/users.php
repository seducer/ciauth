<?php if($users):?>
<table class="table tableborder">
    <thead>
        <tr>
            <th>#</th>
            <th>Login <?php echo $this->ciauth_lib->displaySort('username');?></th>
            <th>Firstname <?php echo $this->ciauth_lib->displaySort('firstname');?></th>
            <th>Lastname</th>
            <th>Gender</th>
            <th>Date of birth <?php echo $this->ciauth_lib->displaySort('dateofbirth');?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $key => $user):?>
        <tr>
            <td><?php echo $key + 1;?></td>
            <td>
                <a href="/admin/user/<?php echo $user->userid;?>"><?php echo $user->username;?></a>
                
            </td>
            <td><?php echo $user->firstname;?></td>
            <td><?php echo $user->lastname;?></td>
            <td><?php echo $user->gender;?></td>
            <td><?php echo $user->dateofbirth;?></td>
            <td>
                <a href="/admin/edit/<?php echo $user->userid;?>"><i class="glyphicon glyphicon-edit" aria-hidden="true"></i></a>
                <a href="/admin/removeuser/<?php echo $user->userid;?>" onclick="if (!confirm('Удалить?')) return false;"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></a>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?php echo $this->pagination->create_links();?>
<?php endif;?>