# -*- coding: utf-8 -*-
"""
Created on Wed May 17 00:42:23 2023

@author: Lapcom Store
"""
import numpy as np 
import sys
import json
import pandas as pd 
import matplotlib.pyplot as plt 
from sklearn.linear_model import LinearRegression
import seaborn as sns 
sns.set()

df = pd.read_csv('./dataa.csv')
# print(df.columns)
# print(len(df))
# print(df.head())
# print(df.info())

# print(sys.argv[1])

data = sys.argv[1]

array_list = data.split(',')

# # Print the list
# print(array_list[0])
# print(array_list[1])
# print(array_list[2])
# print(array_list[3])
# print(array_list[4])
# print(array_list[5])
# print(array_list[6])
# print(array_list[7])
# print(array_list[8])

# try:
#     # Attempt to decode the JSON string
#     data = json.loads(sys.argv[1][0])
#     # Access or perform operations on the JSON data
#     print(data)
# except json.decoder.JSONDecodeError as e:
#     # Handle the JSONDecodeError
#     print("Invalid JSON string:", e)
# # json_data = json.loads(sys.argv[1])

# # Replace double quotes with single quotes




input_data = {
    'Brand': array_list[0],
    'Price':int(array_list[1]),
    'Body': array_list[2],
    'Mileage': int(array_list[3]),
    'Engine Type': array_list[4],
    'Registration': array_list[5],
    'Year': int(array_list[6]),
    'EngineV':int(array_list[7]),
    'Model':array_list[8]
}


# Convert input data into a DataFrame
input_df = pd.DataFrame([input_data])


combined_df = pd.concat([df, input_df], ignore_index=True)

# print(combined_df)

# for col in combined_df.columns:
    # print( col,':', combined_df[col].nunique() )
    # print(combined_df[col].value_counts().nlargest(5))
    # print('\n' + '*' * 20 + '\n')
combined_df.columns = combined_df.columns.str.lower().str.replace(' ', '_')

string_columns = list(combined_df.dtypes[combined_df.dtypes == 'object'].index)
# print(string_columns)
for col in string_columns:
    combined_df[col] = combined_df[col].str.lower().str.replace(' ', '_')    
# plt.figure(figsize=(15, 7))

# sns.histplot(df.price, bins=40)
# plt.ylabel('Frequency')
# plt.xlabel('Price')
# plt.title('Distribution of prices')

# plt.show()
#########################3
combined_df['age'] = 2017 - combined_df.year
###############3
combined_df = combined_df.dropna(axis=0)
#############
# plt.figure(figsize=(6, 4))

sns.histplot(combined_df.price[combined_df.price < 80000], bins=40)
# plt.ylabel('Frequency')
# plt.xlabel('Price')
# plt.title('Distribution of prices')

# plt.show()
######################
combined_df['log_price'] = np.log1p(combined_df.price)

# plt.figure(figsize=(6, 4))

# sns.histplot(combined_df.log_price, bins=40)
# plt.ylabel('Frequency')
# plt.xlabel('Log(Price + 1)')
# plt.title('Distribution of prices after log tranformation')

# plt.show()
#####################3
# print(combined_df.price.skew())
# print(combined_df.log_price.skew())
######################3
# print(combined_df.isnull().sum())
#############################3
data_cleaned = combined_df.reset_index(drop=True)
data_cleaned = data_cleaned.drop(['price'], axis = 1)
data_cleaned = data_cleaned.drop(['model'], axis = 1)
####################

# print(string_columns)
Brand = pd.get_dummies(data_cleaned['brand'], drop_first=True)
Body = pd.get_dummies(data_cleaned['body'], drop_first=True)
Engine_type = pd.get_dummies(data_cleaned['engine_type'], drop_first=True)
Registration = pd.get_dummies(data_cleaned['registration'], drop_first=True)
data_cleaned = data_cleaned.drop([ 'brand', 'body', 'engine_type', 'registration'], axis=1)
dataa = pd.concat([Brand,Body,Engine_type,Registration,data_cleaned],axis =1)
########################
target = dataa['log_price']
inputs = dataa.drop(['log_price'], axis = 1)
#################

###################33
from sklearn.preprocessing import StandardScaler

scaler = StandardScaler()
scaler.fit(inputs)
inputs_scaled = scaler.transform(inputs)
###########################
from sklearn.model_selection import train_test_split 
x_train, x_test, y_train, y_test = train_test_split(inputs_scaled, target, test_size = 0.2, random_state =365)
# print(x_train.shape, x_test.shape)
# print(y_train.shape, y_test.shape)
#######################
reg = LinearRegression() 

reg.fit(x_train, y_train) 

y_hat = reg.predict(x_train)
# plt.scatter(y_train, y_hat)

# plt.xlabel('Targets (y_train)',size=18)
# plt.ylabel('Predictions (y_hat)',size=18)

# plt.xlim(6,13)
# plt.ylim(6,13)
# plt.show()
#########
# print(reg.score(x_train,y_train)*100)
# #########
# print(reg.intercept_)
# print(reg.coef_)
reg_summary = pd.DataFrame(inputs.columns.values, columns=['Features'])
reg_summary['Weights'] = reg.coef_
# print(reg_summary)
#########
y_hat_test = reg.predict(x_test)
###################
# plt.scatter(y_test, y_hat_test, alpha=0.2)
# plt.xlabel('Targets (y_test)',size=18)
# plt.ylabel('Predictions (y_hat_test)',size=18)
# plt.xlim(6,13)
# plt.ylim(6,13)
# plt.show()
####################
df_pf = pd.DataFrame(np.exp(y_hat_test), columns = ['Predictions'])

y_test = y_test.reset_index(drop=True)

df_pf['Target'] = np.exp(y_test)
df_pf['Residual'] = df_pf['Target'] - df_pf['Predictions']
df_pf['Difference%'] = np.absolute(df_pf['Residual']/df_pf['Target']*100)

####

# pd.options.display.max_rows = 999

pd.set_option('display.float_format', lambda x: '%.2f' % x)

df_pf.sort_values(by=['Difference%'])

####
from sklearn.metrics import mean_squared_error
from sklearn.metrics import mean_absolute_error

# Accuracy of the Training Model
# print(f"MSE of MODEL is {round(mean_squared_error(y_train, y_hat),2)}")
# print(f"RMSE of MODEL is {round(mean_squared_error(y_train, y_hat, squared=False),2)}")
# print(f"MAE of MODEL is {round(mean_absolute_error(y_train, y_hat),2)}")
# # Accuracy of the Testing predictions
# pred = reg.predict(x_test)
# print(f"MSE of MODEL is {round(mean_squared_error(y_test, pred),2)}")
# print(f"RMSE of MODEL is {round(mean_squared_error(y_test, pred, squared=False),2)}")
# print(f"MAE of MODEL is {round(mean_absolute_error(y_test, pred),2)}")
# print(np.mean(df_pf['Difference%']))
############


import joblib

joblib.dump(reg, 'trained_model_v02.pkl', compress=9)
# load the model from disk
loaded_model = joblib.load('trained_model_v02.pkl')

result = loaded_model.score(x_test, y_test)

# print(result*100)




# Make predictions
predictions = loaded_model.predict(inputs_scaled)
# Print the predicted price (log-transformed)
predicted_price = np.expm1(predictions)
    # print("------------------------------------------------------------------------------------------------------------------")
    # print(type(predicted_price))

dfs = pd.DataFrame(predicted_price)

    # Display the resulting DataFrame
    # print(combined_df)

    # print(dfs[0].iloc[-1] * 35)

print(dfs[0].iloc[-1] * 35)



