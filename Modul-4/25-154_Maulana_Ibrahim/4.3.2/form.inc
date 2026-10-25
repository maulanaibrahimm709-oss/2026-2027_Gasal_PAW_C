<h1>Register</h1>
<form action="processData_self.php" method="POST">
    <fieldset>
        <legend>Person Details</legend>
        <table>
            <tr>
                <td><label for="surname">Surname</label></td>
                <td><input type="text" name="surname" id="surname" value="<?php echo htmlspecialchars($old['surname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
            </tr>
            <tr>
                <td><label for="email">Email address</label></td>
                <td><input type="text" name="email" id="email" value="<?php echo htmlspecialchars($old['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input type="password" name="password" id="password"></td>
            </tr>
            <tr>
                <td><label for="address">Street Address</label></td>
                <td><textarea name="address" id="address" rows="2" cols="20"><?php echo htmlspecialchars($old['address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></td>
            </tr>
            <tr>
                <td><label for="state">State</label></td>
                <td>
                    <select name="state" id="state">
                        <?php
                        $states = ['Queensland', 'New South Wales', 'Victoria', 'Tasmania', 'Western Australia'];
                        foreach ($states as $state) {
                            $selected = (($old['state'] ?? 'Queensland') === $state) ? ' selected' : '';
                            echo '<option value="' . htmlspecialchars($state, ENT_QUOTES, 'UTF-8') . '"' . $selected . '>' .
                                 htmlspecialchars($state, ENT_QUOTES, 'UTF-8') . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr><td></td><td><input type="hidden" name="country" value="Australia"></td></tr>
            <tr>
                <td>Gender</td>
                <td>
                    <input type="radio" name="gender" value="Male" id="male" <?php echo (($old['gender'] ?? '') === 'Male') ? 'checked' : ''; ?>>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" value="Female" id="female" <?php echo (($old['gender'] ?? '') === 'Female') ? 'checked' : ''; ?>>
                    <label for="female">Female</label>
                </td>
            </tr>
            <tr>
                <td>Vegetarian?</td>
                <td><input type="checkbox" name="vegetarian" value="Yes" id="vegetarian" <?php echo isset($old['vegetarian']) ? 'checked' : ''; ?>>
                    <label for="vegetarian">Yes</label>
                </td>
            </tr>
            <tr><td></td><td><input type="submit" value="Submit"> <input type="reset" value="Reset"></td></tr>
        </table>
    </fieldset>
</form>
