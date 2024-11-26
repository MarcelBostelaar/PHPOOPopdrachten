import os
import re

def replace_in_php_files(folder_path, validator_file):
    # Read the content of the validator file
    with open(validator_file, 'r') as file:
        validator_content = file.read()

    # Loop through all PHP files in the folder
    for root, _, files in os.walk(folder_path):
        for file_name in files:
            if file_name.endswith('.php'):
                file_path = os.path.join(root, file_name)

                # Read the content of the PHP file
                with open(file_path, 'r') as php_file:
                    content = php_file.read()

                # Replace #VALIDATORINSERT with #VALIDATORSTART#VALIDATOREND
                content = content.replace(
                    "#VALIDATORINSERT",
                    "#VALIDATORSTART#VALIDATOREND"
                )

                # Replace content between #VALIDATORSTART and #VALIDATOREND
                content = re.sub(
                    r"#VALIDATORSTART.*?#VALIDATOREND",
                    f"#VALIDATORSTART\n{validator_content}\n#VALIDATOREND",
                    content,
                    flags=re.DOTALL
                )

                # Write the updated content back to the PHP file
                with open(file_path, 'w') as php_file:
                    php_file.write(content)

                print(f"Updated: {file_path}")


# Specify folder path and validator file
folder_path = "."
validator_file = "validator.php"

# Execute the replacement
replace_in_php_files(folder_path, validator_file)
