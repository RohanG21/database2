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

public class EnrollValid extends AppCompatActivity {
    Button returnStudent;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.enrollvalid);
        Intent intent = getIntent();
        final String email = intent.getStringExtra("email");
        final String password = intent.getStringExtra("password");
        final String name = intent.getStringExtra("name");
        final String sid = intent.getStringExtra("sid");
        returnStudent = (Button) findViewById(R.id.student_main_btn);
        returnStudent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent returnStudentIntent = new Intent(EnrollValid.this, StudentMain.class);
                returnStudentIntent.putExtra("email", email);
                returnStudentIntent.putExtra("password", password);
                returnStudentIntent.putExtra("name", name);
                returnStudentIntent.putExtra("sid",sid);
                EnrollValid.this.startActivity(returnStudentIntent);
            }
        });
    }
}