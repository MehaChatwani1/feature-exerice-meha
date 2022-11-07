Shopping Cart MVC Pattern in Core Php
index.php acts as a FrontController

Development steps

1- Create a new model with the following name convention {yourModelName}Model.php under the model folder. Models should have mapped database properties and mapped CRUD, stored procedure related calls and business logic.

2- Create your views under /view/{youViewFaile}/ . Views should only contain display logic, for loops and conditions to render the data passed by the controller.

3- Create a new controller using the following name convention {yourControllerName}Controller.php under the controllers folder. Controllers should contain request mappings, mapping logic, small format conversion functions and web related logic.

Important Notes
The products sql file contains a snapshot of the database being used for this example.
