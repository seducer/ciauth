<div class="row">
    <div class="col-md-6">
        <form class="form-horizontal" method="POST">
            
            <div class="form-group <?php if (form_error('inputLogin')) echo 'has-error';?>">
                <label for="inputLogin" class="col-sm-2 control-label">Login</label>
                <div class="col-sm-6">
                    <?php echo form_error('inputLogin'); ?>
                    <input type="text" class="form-control required" name="inputLogin" id="inputLogin" value="<?php echo set_value('inputLogin', ($user ? $user->username : ''));?>" placeholder="Login">
                </div>
            </div>
            
            <div class="form-group <?php if (form_error('inputPassword')) echo 'has-error';?>">
                <label for="inputLogin" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-6">
                    <?php echo form_error('inputPassword'); ?>
                    <input type="password" class="form-control" name="inputPassword" placeholder="Password">
                </div>
            </div>
            
            <div class="form-group">
                <label for="inputLastName" class="col-sm-2 control-label">Lastname</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="inputLastName" placeholder="Lastname" value="<?php echo set_value('inputLastName', ($user ? $user->lastname : ''));?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="inputFirstName" class="col-sm-2 control-label">Firstname</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="inputFirstName" placeholder="Firstname" value="<?php echo set_value('inputFirstName', ($user ? $user->firstname : ''));?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="inputFirstName" class="col-sm-2 control-label">Gender</label>
                <div class="col-sm-3">
                    <select class="form-control" name="inputGender" id="inputGender">
                        <option value=""></option>
                        <?php foreach ($gender as $key => $name):?>
                        <option value="<?php echo $key;?>" <?php if (set_value('inputGender', ($user ? $user->gender : '')) == $key) echo 'selected';?>><?php echo $name;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
            </div>
            
            <div class="form-group <?php if (form_error('inputDateofbirth')) echo 'has-error';?>">
                <label for="inputDateofbirth" class="col-sm-2 control-label">Date of birth</label>
                <div class="col-sm-6">
                    <?php echo form_error('inputDateofbirth'); ?>
                    <input type="text" class="form-control" name="inputDateofbirth" id="inputDateofbirth" value="<?php echo set_value('inputDateofbirth', ($user ? $user->dateofbirth : ''));?>" placeholder="1990-01-01">
                </div>
            </div>

            <?php if (isset($userid)):?>
            
            <input type="hidden" name="userid" value="<?php echo $user->userid;?>">
            <?php endif;?>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-default">Register</button>
                </div>
            </div>
        </form>
    </div>
    
</div>