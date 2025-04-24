package com.database.db2;

import android.app.AlertDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class CreateAccount extends AppCompatActivity {
    EditText nameInput;
    EditText studentIDInput;
    EditText emailInput;
    EditText passwordInput;
    Button createAccount;
    Button returnLogin;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.createaccount);
        nameInput = (EditText) findViewById(R.id.name_input);
        studentIDInput = (EditText) findViewById(R.id.student_id_input);
        emailInput = (EditText) findViewById(R.id.email_input);
        passwordInput = (EditText) findViewById(R.id.password_input);
        createAccount = (Button) findViewById(R.id.create_new_account);
        returnLogin = (Button)  findViewById(R.id.login_btn);
        returnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent loginIntent = new Intent(CreateAccount.this, Login.class);
                CreateAccount.this.startActivity(loginIntent);
            }
        });
        createAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v){
                String name = nameInput.getText().toString();
                String studentID = studentIDInput.getText().toString();
                String email = emailInput.getText().toString();
                String password = passwordInput.getText().toString();
                Response.Listener<String> responseListener = new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.d("SubmitQueryHelp", response);
                            JSONObject jsonResponse = new JSONObject(response);
                            boolean success = jsonResponse.getBoolean("success");
                            if(success){
                                Intent createAccountValidIntent = new Intent(CreateAccount.this, CreateAccountValid.class);
                                CreateAccount.this.startActivity(createAccountValidIntent);
                            } else{
                                AlertDialog.Builder builder = new AlertDialog.Builder(CreateAccount.this);
                                builder.setMessage("Create Account Failed").setNegativeButton("Retry", null).create().show();
                            }
                        } catch (JSONException e) {
                            Log.d("error fails", "it failed");
                            e.printStackTrace();
                        }
                    }
                };

                CreateAccountQueryRequest queryRequest = new CreateAccountQueryRequest(name, studentID, email, password,getString(R.string.url) + "create_account.php", responseListener);
                RequestQueue queue = Volley.newRequestQueue(CreateAccount.this);
                queue.add(queryRequest);
            }

        });
    }
}