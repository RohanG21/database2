package com.database.db2;

import android.content.Intent;
import android.os.Bundle;
import android.widget.EditText;

import androidx.appcompat.app.AppCompatActivity;

import android.app.AlertDialog;
import android.util.Log;
import android.view.View;
import android.widget.Button;

import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class Login extends AppCompatActivity {
    EditText emailInput;
    EditText passwordInput;
    Button loginBtn;
    Button createAccount;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.login);

        emailInput = (EditText) findViewById(R.id.email_input);
        passwordInput = (EditText) findViewById(R.id.password_input);
        loginBtn = (Button) findViewById(R.id.login_btn);
        createAccount = (Button) findViewById(R.id.create_new_account);
        createAccount.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent createAccountIntent = new Intent(Login.this, CreateAccount.class);
                Login.this.startActivity(createAccountIntent);
            }
        });
        loginBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v){
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
                                String type = jsonResponse.getString("type");
                                switch(type) {
                                    case "admin":
                                        Intent adminIntent = new Intent(Login.this, AdminMain.class);
                                        adminIntent.putExtra("email", email);
                                        adminIntent.putExtra("password", password);
                                        Login.this.startActivity(adminIntent);
                                        break;
                                    case "instructor":
                                        Intent instructorIntent = new Intent(Login.this, InstructorMain.class);
                                        instructorIntent.putExtra("email", email);
                                        instructorIntent.putExtra("password", password);
                                        String instructorName = jsonResponse.getString("name");
                                        String instructorID = jsonResponse.getString("inid");
                                        instructorIntent.putExtra("name", instructorName);
                                        instructorIntent.putExtra("inid", instructorID);
                                        Login.this.startActivity(instructorIntent);
                                        break;
                                    default:
                                        Intent studentIntent = new Intent(Login.this, StudentMain.class);
                                        studentIntent.putExtra("email", email);
                                        studentIntent.putExtra("password", password);
                                        String studentName = jsonResponse.getString("name");
                                        String studentID = jsonResponse.getString("sid");
                                        studentIntent.putExtra("name", studentName);
                                        studentIntent.putExtra("sid", studentID);
                                        Login.this.startActivity(studentIntent);
                                        break;
                                }
                            } else{
                                AlertDialog.Builder builder = new AlertDialog.Builder(Login.this);
                                builder.setMessage("Sign In Failed").setNegativeButton("Retry", null).create().show();
                            }
                        } catch (JSONException e) {
                            Log.d("error fails", "it failed");
                            e.printStackTrace();
                        }
                    }
                };

                LoginQueryRequest queryRequest = new LoginQueryRequest(email, password,getString(R.string.url) + "log_in.php", responseListener);
                RequestQueue queue = Volley.newRequestQueue(Login.this);
                queue.add(queryRequest);
            }

        });

    }
}